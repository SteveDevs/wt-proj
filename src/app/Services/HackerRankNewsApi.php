<?php
namespace App\Services;
use App\Models\ItemType;
use App\Models\Item;
use App\Models\ItemChild;
class HackerRankNewsApi{

	const baseUrl = "https://hacker-news.firebaseio.com/v0/";
    const typeStories = [
        'topstories' => 'top',
        'newstories' => 'new',
        'beststories' => 'best',
    ];

    public $createArrayChilds = [];
    public $itemInsert = [];

    public function syncImportantStories()
    {
        $this->get('newstories');
        $this->get('topstories');
        $this->get('beststories');
    }

    /**
     * @param string $typeStories
     * @return array
     */
    private function get(string $typeStories){
		$url = SELF::baseUrl . $typeStories . '.json?print=pretty';
		$decoded = $this->decoded($url);
        //update

        Item::where(SELF::typeStories[$typeStories], 1)->update([
            SELF::typeStories[$typeStories] => 0
        ]);
		foreach($decoded as $d){

            //$itemChilds = ItemChild::select('child_id')->where('parent_id', $d)->pluck('child_id')->toArray();
            //$this->removeChildren($itemChilds);
            //Item::where('id', $d)->delete();

			$url = SELF::baseUrl . 'item/' . $d . '.json?print=pretty';
            $item = $this->decoded($url);

            $exists = Item::where('id', $item->id)->first();
            if($exists === null){
                $type = $this->checkTypeInsert($item->type);
                $itemObj = new Item();
                $itemObj->id = $item->id;
                $itemObj->parent_id = $item->parent ?? null;
                $itemObj->by = $item->by ?? null;
                $itemObj->title = $item->title ?? null;
                $itemObj->text  = $item->text ?? null;
                $itemObj->time = $item->time ?? null;
                $itemObj->type = $type ?? null;
                $itemObj->save();
            }

            Item::where('id', $d)->where('type', 1)->update([
                SELF::typeStories[$typeStories] => 1
            ]);

            if(isset($item->kids)){
                $this->recursivelyCreateChildren($item->id, $item->kids);
            }
		}
	}

    public function recursivelyCreateChildren($parentId, $itemChilds)
    {
        $itemChildInner = [];

        foreach ($itemChilds as $itemChild){
            $url = SELF::baseUrl . 'item/' . $itemChild . '.json?print=pretty';
            $item = $this->decoded($url);

            $existsChild = ItemChild::where('parent_id', $parentId)->where('child_id', $itemChild)->first();
            if($existsChild === null){
                $this->createArrayChilds[] = [
                    'parent_id' => $parentId,
                    'child_id' => $itemChild
                ];
            }
            $typeId = isset($item->type) ? $this->checkTypeInsert($item->type) : null;
            $existsItem = Item::where('id', $itemChild)->first();
            if($existsItem === null){
                $this->itemInsert[] = [
                    'parent_id' => $parentId,
                    'id' => $itemChild,
                    'by'        => $item->by ?? null,
                    'title'     => $item->title ?? null,
                    'text'     => $item->text ?? null,
                    'time'      => $item->time ?? null,
                    'type'      => $typeId,
                ];
            }

            if(isset($item->kids)){
                foreach ($item->kids as $kid){
                    $itemChildInner[]['parent'] = $kid;
                    $itemChildInner[]['children'] = ItemChild::select('child_id')->where('parent_id', $kid)->pluck('child_id')->toArray();
                    $exits = ItemChild::where('parent_id', $item->id)->where('child_id', $kid)->first();
                    if($exits === null){
                        $this->createArrayChilds[] = [
                            'parent_id' => $item->id,
                            'child_id' => $kid
                        ];
                    }

                }
            }
        }
        if(count($this->createArrayChilds) > 0){
            ItemChild::insert($this->createArrayChilds);
            $this->createArrayChilds = [];
        }
        if(count($this->itemInsert) > 0){
            Item::insert($this->itemInsert);
            $this->itemInsert = [];
        }
        if(count($itemChildInner) > 0){
            foreach($itemChildInner as $itemChildInn){
                if(isset($itemChildInn['parent'], $itemChildInn['children'])){
                    $this->recursivelyCreateChildren($itemChildInn['parent'], $itemChildInn['children']);
                }
            }

        }
    }

    public function removeChildren($itemChilds)
    {
        $moreDeletes = [];
        foreach ($itemChilds as $itemChild){
            array_merge($moreDeletes, ItemChild::select('child_id')->where('parent_id', $itemChild)->get()->pluck('child_id')->toArray());
            Item::where('id', $itemChild)->delete();
        }
        if(count($moreDeletes) > 0){
            $this->removeChildren($moreDeletes);
        }
    }

	public function decoded(string $url){
		$json = file_get_contents($url);
		return json_decode($json);
	}

	public function checkTypeInsert($name)
	{
		$typeQuery = ItemType::where('name', $name);
		if($typeQuery->exists()){
			return $typeQuery->first()->id;
		}else{
			$typeModel = new ItemType();
			$typeModel->name = $name;
			$typeModel->save();
			return $typeModel->id;
		}
	}
}
