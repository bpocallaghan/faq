<?php

namespace Bpocallaghan\FAQ\Controllers\Admin;

use App\Http\Requests;
use Illuminate\Http\Request;
use Bpocallaghan\FAQ\Models\FAQ;
use Bpocallaghan\FAQ\Models\FaqCategory;
use Bpocallaghan\Titan\Http\Controllers\Admin\AdminController;
use Illuminate\Http\Response;

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

    /**
     * Export FAQ data in various formats
     *
     * @param Request $request
     * @return Response
     */
    public function export(Request $request)
    {
        $format = $request->get('format', 'csv');
        $categoryId = $request->get('category_id');
        
        // Get FAQ data with categories
        $query = FAQ::with('category');
        
        if ($categoryId) {
            $query->where('category_id', $categoryId);
        }
        
        $faqs = $query->orderBy('list_order')->get();
        
        switch (strtolower($format)) {
            case 'json':
                return $this->exportJson($faqs);
            case 'csv':
            default:
                return $this->exportCsv($faqs);
        }
    }

    /**
     * Export data as CSV
     *
     * @param $faqs
     * @return Response
     */
    private function exportCsv($faqs)
    {
        $filename = 'faq_export_' . date('Y-m-d_H-i-s') . '.csv';
        
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
        ];
        
        $callback = function() use ($faqs) {
            $file = fopen('php://output', 'w');
            
            // CSV headers
            fputcsv($file, [
                'ID',
                'Question',
                'Answer',
                'Category',
                'List Order',
                'Total Reads',
                'Helpful Yes',
                'Helpful No',
                'Created At',
                'Updated At'
            ]);
            
            // CSV data
            foreach ($faqs as $faq) {
                fputcsv($file, [
                    $faq->id,
                    $faq->question,
                    strip_tags($faq->answer),
                    $faq->category->name ?? '',
                    $faq->list_order,
                    $faq->total_read,
                    $faq->helpful_yes,
                    $faq->helpful_no,
                    $faq->created_at,
                    $faq->updated_at
                ]);
            }
            
            fclose($file);
        };
        
        return response()->stream($callback, 200, $headers);
    }

    /**
     * Export data as JSON
     *
     * @param $faqs
     * @return Response
     */
    private function exportJson($faqs)
    {
        $filename = 'faq_export_' . date('Y-m-d_H-i-s') . '.json';
        
        $data = $faqs->map(function($faq) {
            return [
                'id' => $faq->id,
                'question' => $faq->question,
                'answer' => $faq->answer,
                'category' => $faq->category->name ?? '',
                'category_id' => $faq->category_id,
                'list_order' => $faq->list_order,
                'total_read' => $faq->total_read,
                'helpful_yes' => $faq->helpful_yes,
                'helpful_no' => $faq->helpful_no,
                'created_at' => $faq->created_at,
                'updated_at' => $faq->updated_at
            ];
        });
        
        return response()->json($data)
            ->header('Content-Disposition', 'attachment; filename="' . $filename . '"')
            ->header('Content-Type', 'application/json');
    }
}