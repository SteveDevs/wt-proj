<?php

namespace App\Http\Controllers;
use App\Models\Item;
use App\Models\ItemChild;
use Carbon\Carbon;

class HackerController extends Controller
{
    private $attributes;
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $data = [];
        $data['best'] = Item::where('best', 1)->get();
        foreach ($data['best'] as $key => $best){
            $childItems = ItemChild::select('child_id')->where('parent_id', $top->id)->get()->pluck('child_id')->toArray();
            $itemVals = Item::whereIn('id', $childItems)->get();
            $data['best'][$key]->comments = $itemVals;
            $data['best'][$key]->time = Carbon::createFromTimestamp($data['best'][$key]->time)->toDateTimeString();
        }

        $data['top'] = Item::where('top', 1)->get();
        foreach ($data['top'] as $key => $top){
            $childItems = ItemChild::select('child_id')->where('parent_id', $top->id)->get()->pluck('child_id')->toArray();

            $itemVals = Item::whereIn('id', $childItems)->get();
            $data['top'][$key]->comments = $itemVals;
            $data['top'][$key]->time = Carbon::createFromTimestamp($data['top'][$key]->time)->toDateTimeString();
        }

        $data['new'] = Item::where('new', 1)->get();
        foreach ($data['new'] as $key => $best){
            $childItems = ItemChild::select('child_id')->where('parent_id', $top->id)->get()->pluck('child_id')->toArray();
            $itemVals = Item::whereIn('id', $childItems)->get();
            $data['new'][$key]->comments = $itemVals;
            $data['new'][$key]->time = Carbon::createFromTimestamp($data['new'][$key]->time)->toDateTimeString();
        }

        return view('news.index', ['data' => $data]);
    }

}
