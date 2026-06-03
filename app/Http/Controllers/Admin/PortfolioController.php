<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Portfolio;
use Illuminate\Http\Request;

class PortfolioController extends Controller
{
    public function index()
    {
        $portfolios = Portfolio::latest()->paginate(10);

        return view(
            'admin.portfolios.index',
            compact('portfolios')
        );
    }

    public function create()
    {
        return view('admin.portfolios.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'image' => 'required|image'
        ]);

        $image = time().'.'.$request->image->extension();

        $request->image->move(
            public_path('uploads/portfolios'),
            $image
        );

        Portfolio::create([
            'image' => $image
        ]);

        return redirect()
            ->route('portfolios.index')
            ->with('success','Portfolio Added');
    }

    public function edit($id)
    {
        $portfolio = Portfolio::findOrFail($id);

        return view(
            'admin.portfolios.edit',
            compact('portfolio')
        );
    }

    public function update(Request $request, $id)
    {
        $portfolio = Portfolio::findOrFail($id);

        $image = $portfolio->image;

        if($request->hasFile('image'))
        {
            $image = time().'.'.$request->image->extension();

            $request->image->move(
                public_path('uploads/portfolios'),
                $image
            );
        }

        $portfolio->update([
            'image' => $image
        ]);

        return redirect()
            ->route('portfolios.index')
            ->with('success','Portfolio Updated');
    }

    public function destroy($id)
    {
        Portfolio::destroy($id);

        return back()
            ->with('success','Portfolio Deleted');
    }
}