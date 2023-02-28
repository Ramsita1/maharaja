<?php

namespace App\Http\Controllers\Admin\SiteMap;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Input;
use DB, DateTime, Session, Redirect, Auth;
use App\Posts;
use App\Terms;
use App\Links;
use App\PostMetas;

class SiteMapController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */ 
    public function index(Request $request)
    {
        $hasUrl = false;
        $sitemapPath = base_path('sitemap.xml');
        $sitemapPath = str_replace('backend/', '', $sitemapPath);
        $xmlObjects = simplexml_load_file($sitemapPath);
        
        $xmlRow = '';
        $existRow = false;
        if (!empty($xmlObjects->url)) {
            foreach($xmlObjects->url as $xmlObject){   
                $xmlRow .= '<url>
                      <loc>'.$xmlObject->loc.'</loc>
                      <lastmod>'.$xmlObject->lastmod.'</lastmod>
                      <priority>'.$xmlObject->priority.'</priority>
                   </url>';             
            }
        } 

        $xmlContent = '<?xml version="1.0" encoding="UTF-8"?>
            <urlset
                  xmlns="http://www.sitemaps.org/schemas/sitemap/0.9"
                  xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance"
                  xsi:schemaLocation="http://www.sitemaps.org/schemas/sitemap/0.9
                        http://www.sitemaps.org/schemas/sitemap/0.9/sitemap.xsd">
            <!-- created with Free Online Sitemap Generator www.xml-sitemaps.com -->
               '.$xmlRow.'
            </urlset>';

        $view = 'Admin.SiteMap.Index';
        return view('Admin', compact('view','xmlContent'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {   
        $post_content = $request->input('post_content');

        $xmlContent = ($post_content);

        $sitemapPath = base_path('sitemap.xml');
        $sitemapPath = str_replace('backend/', '', $sitemapPath);

        $dom = new \DOMDocument;
        $dom->preserveWhiteSpace = FALSE;
        $dom->loadXML($xmlContent);        
        $dom->save($sitemapPath);

        Session::flash ( 'success', "SiteMap Saved Successfully." );
        return Redirect::back();  
    }

}
