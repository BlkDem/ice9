/**
 * UncosNews
 *
 * Copyright 2011 by Alexander "unglued" Matrosov unglud@gmail.com http://unglued.ru/
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
 * с программой. В случае её отсутствия, посмотрите  http://www.gnu.org/licenses/.
 *
 * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * */
UncosNews.grid.UncosNews = function(config) {
    config = config || {};
		var binaryColumn = new Ext.ux.grid.CheckColumn({
       header: _('uncosnews.field.active')
        ,dataIndex: 'active'
        ,width: 20
        ,sortable: true
   		 ,onMouseDown: function(e, t){
            var rowData = "";
            if(t.className && t.className.indexOf('x-grid3-cc-'+this.id) != -1){
                e.stopEvent();
								var that = this;
                var index = this.grid.getView().findRowIndex(t);
                var record = this.grid.store.getAt(index);
                record.set(this.dataIndex, !record.data[this.dataIndex]);
                rowData = record.data;//save row records. will be used in the ajax request
            }
            if(rowData){
                Ext.Ajax.request({
                	url : UncosNews.config.connectorUrl , 
                	params : { action : 'mgr/uncosnews/updateFromGrid', data: Ext.util.JSON.encode(rowData)},
                	method: 'POST',
                	success: function ( result, request ){ that.grid.getStore().commitChanges(); },
                	failure: function ( result, request) {	Ext.MessageBox.alert(_('uncosnews.err'), _('uncosnews.err_save'));} 
                });
            }
        }
    });
    Ext.applyIf(config,{
        id: 'uncosnews-grid-news'
        ,url: UncosNews.config.connectorUrl
        ,baseParams: { action: 'mgr/uncosnews/getList' }
        ,fields: ['id','title','desc','text','active']
        ,paging: true
				,autosave : true
				,save_action: 'mgr/uncosnews/updateFromGrid'
        ,remoteSort: true
				,plugins: binaryColumn
        ,anchor: '97%'
        ,autoExpandColumn: 'text'
        ,columns: [{
            header: _('id')
            ,dataIndex: 'id'
            ,sortable: true
            ,width: 10
        },{
            header: _('uncosnews.field.title')
            ,dataIndex: 'title'
            ,sortable: true
            ,width: 100
            ,editor: { xtype: 'textfield', allowBlank: false }
        },{
            header: _('uncosnews.field.desc')
            ,dataIndex: 'desc'
            ,sortable: false
            ,width: 130
            ,editor: { xtype: 'textarea', allowBlank: false  }
        },{
            header: _('uncosnews.field.text')
            ,dataIndex: 'text'
            ,sortable: false
            ,width: 250
            ,editor: { xtype: 'htmleditor', allowBlank: false  }
        },binaryColumn]
				,tbar:[{
					xtype: 'textfield'
					,id: 'uncosnews-search-filter'
					,emptyText: _('uncosnews.search...')
					,listeners: {
						'change': {fn:this.search,scope:this}
						,'render': {fn: function(cmp) {
							new Ext.KeyMap(cmp.getEl(), {
								key: Ext.EventObject.ENTER
									,fn: function() {
										this.fireEvent('change',this);
										this.blur();
										return true;
									}
									,scope: cmp
								});
						},scope:this}
					}
			},{
				 text: _('uncosnews.news_create')
				 ,handler: { xtype: 'uncosnews-window-news-create' ,blankValues: true }
			}]
			,getMenu: function() {
					var m = [{
							text: _('uncosnews.news_update')
							,handler: this.updateUncosNews
					},'-',{
							text: _('uncosnews.news_remove')
							,handler: this.removeUncosNews
					}];
					this.addContextMenuItem(m);
					return true;
			}
    });
    UncosNews.grid.UncosNews.superclass.constructor.call(this,config)
};
Ext.extend(UncosNews.grid.UncosNews,MODx.grid.Grid,{
    search: function(tf,nv,ov) {
        var s = this.getStore();
        s.baseParams.query = tf.getValue();
        this.getBottomToolbar().changePage(1);
        this.refresh();
    }
		,updateUncosNews: function(btn,e) {
			if (!this.updateUncosNewsWindow) {
					this.updateUncosNewsWindow = MODx.load({
							xtype: 'uncosnews-window-news-update'
							,record: this.menu.record
							,listeners: {
									'success': {fn:this.refresh,scope:this}
							}
					});
			} else {
					this.updateUncosNewsWindow.setValues(this.menu.record);
			}
			this.updateUncosNewsWindow.show(e.target);
		}
		,removeUncosNews: function() {
				MODx.msg.confirm({
						title: _('uncosnews.news_remove')
						,text: _('uncosnews.news_remove_confirm')
						,url: this.config.url
						,params: {
								action: 'mgr/uncosnews/remove'
								,id: this.menu.record.id
						}
						,listeners: {
								'success': {fn:this.refresh,scope:this}
						}
				});
		}

});
Ext.reg('uncosnews-grid-news',UncosNews.grid.UncosNews);

UncosNews.window.UpdateUncosNews = function(config) {
    config = config || {};
    Ext.applyIf(config,{
				width:800
        ,title: _('uncosnews.news_update')
        ,url: UncosNews.config.connectorUrl
        ,baseParams: {
            action: 'mgr/uncosnews/update'
        }
        ,fields: [{
            xtype: 'hidden'
            ,name: 'id'
        },{
            xtype: 'textfield'
            ,fieldLabel: _('uncosnews.field.title')
            ,name: 'title'
            ,width: 300
        },{
            xtype: 'textarea'
            ,fieldLabel: _('uncosnews.field.desc')
            ,name: 'desc'
            ,width: 300
        },{
						xtype: 'htmleditor'
            ,fieldLabel: _('uncosnews.field.text')
            ,name: 'text'
						,width: 600
        },{
						xtype: 'checkbox'
            ,fieldLabel: _('uncosnews.field.active')
            ,name: 'active'
        }]
    });
    UncosNews.window.UpdateUncosNews.superclass.constructor.call(this,config);
};
Ext.extend(UncosNews.window.UpdateUncosNews,MODx.Window);
Ext.reg('uncosnews-window-news-update',UncosNews.window.UpdateUncosNews);

UncosNews.window.CreateUncosNews = function(config) {
    config = config || {};
    Ext.applyIf(config,{
				width:800
        ,title: _('uncosnews.news_create')
        ,url: UncosNews.config.connectorUrl
        ,baseParams: {
            action: 'mgr/uncosnews/create'
        }
        ,fields: [{
            xtype: 'textfield'
            ,fieldLabel: _('uncosnews.field.title')
            ,name: 'title'
            ,width: 300
        },{
            xtype: 'textarea'
            ,fieldLabel: _('uncosnews.field.desc')
            ,name: 'desc'
            ,width: 300
        },{
						xtype: 'htmleditor'
            ,fieldLabel: _('uncosnews.field.text')
            ,name: 'text'
						,width: 600
        },{
						xtype: 'checkbox'
            ,fieldLabel: _('uncosnews.field.active')
            ,name: 'active',
						checked: true
        }]
    });
    UncosNews.window.CreateUncosNews.superclass.constructor.call(this,config);
};
Ext.extend(UncosNews.window.CreateUncosNews,MODx.Window);
Ext.reg('uncosnews-window-news-create',UncosNews.window.CreateUncosNews);