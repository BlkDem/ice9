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
 * This file is the main class file for UncosNews.
 *
 * @copyright Copyright (C) 2011, Alexander "unglued" Matrosov 
 * @author Alexander "unglued" Matrosov <unglud@gmail.com> <http://unglued.ru/>
 * @license http://www.gnu.org/licenses/gpl-3.0.html GNU Public License v3
 * @package uncosnews
 */
class UncosNews {
    public $modx;
    public $config = array();
    function __construct(modX &$modx,array $config = array()) {
        $this->modx =& $modx;
 
        $basePath = $this->modx->getOption('uncosnews.core_path',$config,$this->modx->getOption('core_path').'components/uncosnews/');
        $assetsUrl = $this->modx->getOption('uncosnews.assets_url',$config,$this->modx->getOption('assets_url').'components/uncosnews/');
        $this->config = array_merge(array(
						'copyright'=>'Uncos News v.1.0 | Dev by Alexander <a href="http://unglued.ru/makingof/uncosnews/" target="_blank">unglued</a> Matrosov',
            'basePath' => $basePath,
            'corePath' => $basePath,
            'modelPath' => $basePath.'model/',
            'processorsPath' => $basePath.'processors/',
            'chunksPath' => $basePath.'elements/chunks/',
            'jsUrl' => $assetsUrl.'js/',
            'cssUrl' => $assetsUrl.'css/',
            'assetsUrl' => $assetsUrl,
            'connectorUrl' => $assetsUrl.'connector.php',
        ),$config);
				
				$this->modx->addPackage('uncosnews',$this->config['modelPath']);
    }
		
		public function getChunk($name,$properties = array()) {
				$chunk = null;
				if (!isset($this->chunks[$name])) {
						$chunk = $this->_getTplChunk($name);
						if (empty($chunk)) {
								$chunk = $this->modx->getObject('modChunk',array('name' => $name));
								if ($chunk == false) return false;
						}
						$this->chunks[$name] = $chunk->getContent();
				} else {
						$o = $this->chunks[$name];
						$chunk = $this->modx->newObject('modChunk');
						$chunk->setContent($o);
				}
				$chunk->setCacheable(false);
				return $chunk->process($properties);
		}
		
		public function initialize($ctx = 'web') {
			 switch ($ctx) {
						case 'mgr':
								$this->modx->lexicon->load('uncosnews:default');
								if (!$this->modx->loadClass('uncosnewsControllerRequest',$this->config['modelPath'].'uncosnews/request/',true,true)) {
									 return 'Could not load controller request handler from '.$this->config['modelPath'].'uncosnews/request/.';
								}
								$this->request = new uncosnewsControllerRequest($this);
								return $this->request->handleRequest();
						break;
				}
				return true;
		}
		 
		private function _getTplChunk($name,$postfix = '.chunk.htm') {
				$chunk = false;
				$f = $this->config['chunksPath'].strtolower($name).$postfix;
				if (file_exists($f)) {
						$o = file_get_contents($f);
						$chunk = $this->modx->newObject('modChunk');
						$chunk->set('name',$name);
						$chunk->setContent($o);
				}
				return $chunk;
		}
}