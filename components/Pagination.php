<?php

namespace App\components;

/**
 * Class Pagination for generating pagination.
 */
class Pagination
{
    /**
     * @var int - The number of navigation links on a page
     */
    private $max = 10;
    /**
     * @var string - GET KEY, in which the page number is written
     */
    private $index = 'page';
    /**
     * @var int - current page
     */
    private $current_page;
    /**
     * @var int - Total number of records
     */
    private $total;
    /**
     * @var int - The number of records on a page
     */
    private $limit;

    /**
     * Running the necessary data for navigation.
     *
     * @param int $total       - Total number of records
     * @param int $currentPage - the number of current page
     * @param int $limit       - The number or records on a page
     * @param int $index       - url to key
     */
    public function __construct($total, $currentPage, $limit, $index)
    {
        $this->total = $total; // Setting the total number of records
        $this->limit = $limit; // Setting the number of records on a page
        $this->index = $index; // Setting the key to url
        $this->amount = $this->amount(); // Setting the number of pages
        $this->setCurrentPage($currentPage); // Setting the number of current page
    }

    /**
     * To display links.
     *
     * @return string HTML- code with navigation links
     */
    public function get()
    {
        $links = null; // for recording links
        $limits = $this->limits(); // Receiving the end point of cycle
        $html = '<ul class="pagination">';

        for ($page = $limits[0]; $page <= $limits[1]; $page++) { // links generation
            if ($page == $this->current_page) { // Adding a class "active" to the current page
                $links .= '<li class="active"><a href="#">'.$page.'</a></li>';
            } else {
                $links .= $this->generateHtml($page); // link generation
            }
        }
        if (!is_null($links)) { // whether links are made
            if ($this->current_page > 1) { // if current page isn't first
                $links = $this->generateHtml(1, '&lt;').$links;
            }
            if ($this->current_page < $this->amount) {
                $links .= $this->generateHtml($this->amount, '&gt;');
            }  // Setting a link to the last page
        }
        $html .= $links.'</ul>';

        return $html; // Returning HTML
    }

    /**
     * To generate the HTML code of the link.
     *
     * @param int      $page - page number
     * @param int|null $text - page number or null
     *
     * @return string
     */
    private function generateHtml($page, $text = null)
    {
        if (!$text) { // if link text isn't set
            $text = $page;
        } // Setting a text as a page number
            $currentURI = rtrim($_SERVER['REQUEST_URI'], '/').'/';
        $currentURI = preg_replace('~/page-[0-9]+~', '', $currentURI); // Creating HTML code of link and returning it
        return  '<li><a href="'.$currentURI.$this->index.$page.'">'.$text.'</a></li>';
    }

    /**
     * To receiving a start point.
     *
     * @return array with the beginning and end of the count
     */
    private function limits()
    {
        $left = $this->current_page - round($this->max / 2); // Computing the links on the left (for the active link to be in the middle)
        $start = $left > 0 ? $left : 1; // Calculating a start page

        if ($start + $this->max <= $this->amount) { // if there are at least $this->max pages ahead
            $end = $start > 1 ? $start + $this->max : $this->max; // Setting the end of the cycle on $this->max pages or just on min
        } else {
            $end = $this->amount; // total number of pages
            $start = $this->amount - $this->max > 0 ? $this->amount - $this->max : 1; // the start minus max from the end
        }

        return [$start, $end]; // returning the first and the end points
    }

    /**
     * To set the current page.
     *
     * @param int $currentPage - current page
     *
     * @return void
     */
    private function setCurrentPage($currentPage)
    {
        $this->current_page = $currentPage; // Receiving the page number
        if ($this->current_page > 0) { // whether current page is more than 0
            if ($this->current_page > $this->amount) { // whether current page is less of total number of pages
                $this->current_page = $this->amount;
            } // Setting the page on the last
        } else {
            $this->current_page = 1;
        } // Setting the page on the first
    }

    /**
     * To receiving the total number of pages.
     *
     * @return int - number of pages
     */
    private function amount()
    {
        return ceil($this->total / $this->limit); // Dividing and returning
    }
}
