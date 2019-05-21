<?php

namespace Bpocallaghan\FAQ\Controllers\Admin;

use App\Http\Requests;
use Illuminate\Http\Request;
use Bpocallaghan\FAQ\Models\FAQ;
use Bpocallaghan\FAQ\Models\FaqCategory;
use Bpocallaghan\Titan\Http\Controllers\Admin\AdminController;

class OrderController extends AdminController
{
    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index()
    {
        $html = $this->getOrderHtml();

        return $this->view('faq::order')->with('itemsHtml', $html);
    }

    /**
     * Update the order
     * @param Request $request
     * @return array
     */
    public function updateOrder(Request $request)
    {
        $navigation = json_decode($request->get('list'), true);

        foreach ($navigation as $key => $nav) {
            $row = $this->updateListOrder($nav['id'], ($key + 1));
        }

        return ['result' => 'success'];
    }

    /**
     * Generate the nestable html
     *
     * @param null $parent
     *
     * @return string
     */
    private function getOrderHtml($parent = null)
    {
        $html = '<ol class="dd-list">';

        $items = FaqCategory::with('faqs')->orderBy('name')->get();
        foreach ($items as $key => $item) {

            foreach ($item->faqs as $k => $faq) {
                $html .= '<li class="dd-item" data-id="' . $faq->id . '">';
                $html .= '<div class="dd-handle">';
                $html .= '<strong>' . $item->name . '</strong> - ' . $faq->question . ' ' . ' <span style="float:right"> ' . $faq->answer_summary . ' </span></div>';
                $html .= '</li>';
            }

            $html .= '<hr/>';
        }

        $html .= '</ol>';

        return (count($items) >= 1 ? $html : '');
    }

    /**
     * Update Navigation Item, with new list order and parent id (list and parent can change)
     *
     * @param     $id
     * @param     $listOrder
     * @param int $parentId
     *
     * @return mixed
     */
    private function updateListOrder($id, $listOrder, $parentId = 0)
    {
        $row = FAQ::find($id);
        $row->list_order = $listOrder;
        $row->save();

        return $row;
    }
}