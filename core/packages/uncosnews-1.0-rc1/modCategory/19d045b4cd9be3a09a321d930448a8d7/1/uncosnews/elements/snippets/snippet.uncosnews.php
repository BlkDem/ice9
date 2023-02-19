<?php
/**
 * UncosNews
 *
 * Copyright 2011 by Alexander "unglued" Matrosov <unglud@gmail.com> <http://unglued.ru/>
 *
 * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * *
 * This file is part of UncosNews, a simple news component for MODx Revolution.
 *
 * UncosNews is free software; you can redistribute it and/or modify it under the
 * terms of the GNU General Public License as published by the Free Software
 * Foundation; either version 2 of the License, or (at your option) any later
 * version.
 *
 * UncosNews is distributed in the hope that it will be useful, but WITHOUT ANY
 * WARRANTY; without even the implied warranty of MERCHANTABILITY or FITNESS FOR
 * A PARTICULAR PURPOSE. See the GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License along with
 * UncosNews; if not, write to the Free Software Foundation, Inc., 59 Temple Place,
 * Suite 330, Boston, MA 02111-1307 USA
 *
 * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * *
 * Этот файл является частью UncosNews, простого новостного компанента для MODx Revolution.
 *
 * UncosNews является свободным программным обеспечением. Вы можете
 * распространять и/или модифицировать её согласно условиям Стандартной
 * Общественной Лицензии GNU, опубликованной Фондом Свободного Программного
 * Обеспечения, версии 3 или, по Вашему желанию, любой более поздней версии.
 *
 * UncosNews распространяется в надежде, что она будет полезной, но БЕЗ
 * ВСЯКИХ ГАРАНТИЙ, в том числе подразумеваемых гарантий ТОВАРНОГО СОСТОЯНИЯ ПРИ
 * ПРОДАЖЕ и ГОДНОСТИ ДЛЯ ОПРЕДЕЛЁННОГО ПРИМЕНЕНИЯ. Смотрите Стандартную
 * Общественную Лицензию GNU для получения дополнительной информации.
 *
 * Вы должны были получить копию Стандартной Общественной Лицензии GNU вместе
 * с программой. В случае её отсутствия, посмотрите <http://www.gnu.org/licenses/>.
 *
 * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * */
 
 /**
 * UncosNews
 *
 * A simple news component
 *
 * @name UncosNews
 * @author Alexander "unglued" Matrosov <unglud@gmail.com> <http://unglued.ru/>
 * @package uncosnews
 */
 
$defaulUncosNewsCorePath = $modx->getOption('core_path').'components/uncosnews/';
$uncosNewsCorePath = $modx->getOption('uncosnews.core_path',null,$defaulUncosNewsCorePath);
$uncos = $modx->getService('uncos','UncosNews',$uncosNewsCorePath.'model/uncosnews/',$scriptProperties);
if (!($uncos instanceof UncosNews)) return '';
$id = $modx->resource->get('id');

/* setup default properties */
$tpl = $modx->getOption('tpl',$scriptProperties,'rowTpl');
$fulltpl = $modx->getOption('fulltpl',$scriptProperties,'fullTpl');
$sort = $modx->getOption('sort',$scriptProperties,'id');
$dir = $modx->getOption('dir',$scriptProperties,'DESC');
$limit = $modx->getOption('limit',$scriptProperties,false);
$newsPage = $modx->getOption('newsPage',$scriptProperties,$id);
$expand = $modx->getOption('expand',$scriptProperties,true);

 
$output = '';

$c = $modx->newQuery('UncosNewsNews');
$c->where(array('active'=>true));
if(isset($_GET['n']) && $_GET['n']!='' && $expand){
	$c->where(array('id'=>(int)$_GET['n']));
	$tpl = $fulltpl;
	$count = $modx->getCount('UncosNewsNews',$c);
	if(!$count) $modx->sendRedirect($modx->makeUrl($newsPage));
}
if($limit) $c->limit($limit);
$c->sortby($sort,$dir);
$news = $modx->getCollection('UncosNewsNews',$c);

$urlDelim = $modx->getOption('friendly_urls')? '?' : '&';
foreach ($news as $item) {
    $itemArray = $item->toArray();
		$itemArray['url'] = $modx->makeUrl($newsPage).$urlDelim.'n='.$itemArray['id'];
		$author = $item->getOne('CreatedBy');
		$itemArray['user.fullname'] = $author->getOne('Profile')->get('fullname');
		$itemArray['user.username'] = $author->get('username');
		
    $output .= $uncos->getChunk($tpl,$itemArray);
}
 
echo $output;