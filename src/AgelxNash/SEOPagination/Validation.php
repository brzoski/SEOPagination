<?php namespace AgelxNash\SEOPagination;

use App, Redirect, Request;

class Validation{
    /**
     * @param Paginator $pages
     * @return bool|\Illuminate\Http\RedirectResponse|\Illuminate\Pagination\Paginator
     */
    public static function checkPaginate(Paginator &$pages, $keepQuery = null){
        $flag = false;
        $request = Request::get($pages->getEnvironment()->getPageName());
        $cPage = $pages->getCurrentPage();
        if(($pages->isEmpty() && 1!=$cPage) || (1==$cPage && !is_null($request) && (int)$request!=$cPage)){
            if(is_null($keepQuery)){
                $keepQuery = $pages->getEnvironment()->getKeepQuery();
            }
            if($keepQuery){
                $query = array_except( Request::query(), $pages->getEnvironment()->getPageName() );
                $pages->appends($query);
            }
            $action = $pages->getEnvironment()->getActionOnError();
            switch($action){
                case 'abort':{
                    $flag = App::abort(404);
                    break;
                }
                case 'first':{
                    $flag = Redirect::to($pages->getUrl(0), $pages->getEnvironment()->getErrorStatus());
                    break;
                }
                case 'out':{
                    $url = (1 == $pages->getCurrentPage()) ? 0 : $pages->getLastPage();
                    $flag = Redirect::to($pages->getUrl($url), $pages->getEnvironment()->getErrorStatus());
                    break;
                }
                default:{
                    throw new Exceptions\PageNotFound;
                }
            }
        }
        return $flag;
    }
}