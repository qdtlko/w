<?php

namespace App\Http\Controllers\Backend;

use App\Http\Requests\Form\MenuForm;
use App\Models\Menu;
use App\Models\Tree;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;

class MenuController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $menus = Menu::paginate(25);

        return view('backend.menu.index', compact('menus'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $tree = Tree::createLevelTree(Menu::all());

        return view('backend.menu.create', compact('tree'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(MenuForm $request)
    {
        try {
            if (Menu::create($request->all())) {
                return redirect()->back()->withSuccess("新增菜单成功");
            }
        } catch (\Exception $e) {
            return redirect()->back()->withErrors(array('error' => $e->getMessage()))->withInput();
        }

    }

    /**
     * Display the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $menu = Menu::find($id);
        $tree = Tree::createLevelTree(Menu::all());

        return view('backend.menu.edit', compact('menu', 'tree'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function update(MenuForm $request, $id)
    {
        $data = $request->all();
        unset($data['_method']);
        unset($data['_token']);

        try {
            if (Menu::where('id', $id)->update($data)) {
                return redirect()->back()->withSuccess('编辑菜单成功');
            }
        } catch (\Exception $e) {
            return redirect()->back()->withErrors(array('error' => $e->getMessage()))->withInput();
        }

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $childMenus = Menu::where('parent_id', '=', $id)->get()->toArray();

        if (!empty($childMenus)) {
            return redirect()->back()->withErrors("请先删除其下级分类");
        }

        try {
            if (Menu::destroy($id)) {
                return redirect()->back()->withSuccess('删除菜单成功');
            }
        } catch (\Exception $e) {
            return redirect()->back()->withErrors(array('error' => $e->getMessage()));
        }

    }
}
