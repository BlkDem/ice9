<?php  return 'echo "<div id=\\"layoutSidenav_nav\\">
                <nav class=\\"sidenav shadow-right sidenav-light\\">
                    <div class=\\"sidenav-menu\\">
                        <div class=\\"nav accordion\\" id=\\"accordionSidenav\\">
                            <div class=\\"sidenav-menu-heading\\">Сервер</div>
                            <a class=\\"nav-link collapsed\\" href=\\"javascript:void(0);\\" data-toggle=\\"collapse\\" data-target=\\"#collapseDashboards\\" aria-expanded=\\"false\\" aria-controls=\\"collapseDashboards\\">
                                <div class=\\"nav-link-icon\\"><i data-feather=\\"activity\\"></i></div>
                                База данных
                                <div class=\\"sidenav-collapse-arrow\\"><i class=\\"fas fa-angle-down\\"></i></div>
                            </a>
                            <div class=\\"collapse\\" id=\\"collapseDashboards\\" data-parent=\\"#accordionSidenav\\">
                                <nav class=\\"sidenav-menu-nested nav accordion\\" id=\\"accordionSidenavPages\\">
                                    <a class=\\"nav-link\\" href=\\"statistic.html\\">
                                        Статистика
                                        
                                    </a>
                                </nav>
                            </div>
                            <div class=\\"sidenav-menu-heading\\">Данные</div>
                            <a class=\\"nav-link collapsed\\" href=\\"javascript:void(0);\\" data-toggle=\\"collapse\\" data-target=\\"#collapsePages\\" aria-expanded=\\"false\\" aria-controls=\\"collapsePages\\">
                                <div class=\\"nav-link-icon\\"><i data-feather=\\"grid\\"></i></div>
                                Справочники
                                <div class=\\"sidenav-collapse-arrow\\"><i class=\\"fas fa-angle-down\\"></i></div>
                            </a>
                            <div class=\\"collapse\\" id=\\"collapsePages\\" data-parent=\\"#accordionSidenav\\">
                                <nav class=\\"sidenav-menu-nested nav accordion\\" id=\\"accordionSidenavPagesMenu\\">
                                    <a class=\\"nav-link\\" href=\\"owners.html\\">
                                        Пользователи
                                        
                                    </a>
                                    <a class=\\"nav-link\\" href=\\"sensortypes.html\\">
                                        Типы устройств
                                        
                                    </a>
                                    <a class=\\"nav-link\\" href=\\"images.html\\">
                                        Изображения
                                        
                                    </a>
                                    <a class=\\"nav-link\\" href=\\"dictionaries.html\\">
                                        Словари
                                        
                                    </a>
                                </nav>
                            </div>
                            <a class=\\"nav-link collapsed\\" href=\\"javascript:void(0);\\" data-toggle=\\"collapse\\" data-target=\\"#collapseFlows\\" aria-expanded=\\"false\\" aria-controls=\\"collapseFlows\\">
                                <div class=\\"nav-link-icon\\"><i data-feather=\\"repeat\\"></i></div>
                                Устройства
                                <div class=\\"sidenav-collapse-arrow\\"><i class=\\"fas fa-angle-down\\"></i></div>
                            </a>
                            <div class=\\"collapse\\" id=\\"collapseFlows\\" data-parent=\\"#accordionSidenav\\">
                                <nav class=\\"sidenav-menu-nested nav\\">
                                    <!--<a class=\\"nav-link\\" href=\\"monitor.html\\">Контрольная Панель</a>-->
                                    <a class=\\"nav-link\\" href=\\"sensors.html\\">
                                        Управление
                                       
                                    </a>
                                </nav>
                            </div>
                            
                            <div class=\\"sidenav-menu-heading\\">Журналы</div>
                            <a class=\\"nav-link\\" href=\\"activity.html\\">
                                <div class=\\"nav-link-icon\\"><i data-feather=\\"bar-chart\\"></i></div>
                                Активность
                            </a>
                            <!--<a class=\\"nav-link\\" href=\\"tables.html\\">
                                <div class=\\"nav-link-icon\\"><i data-feather=\\"filter\\"></i></div>
                                Команды-->
                            </a>
                        </div>
                    </div>
                    <div class=\\"sidenav-footer\\">
                        <div class=\\"sidenav-footer-content\\">
                            
                            <div class=\\"sidenav-footer-title\\">[[+modx.user.id:isloggedin:is=`1`:then=`<i class=\\"fas fa-user-astronaut fa-2x\\"></i>`:else=`<i class=\\"fas fa-user-times fa-2x\\"></i>`]] 
                            <span style=\\"margin-left: 5px;\\">[[+modx.user.id:userinfo=`username`]] "; 
                            
                            $userid = $modx->user->get(\'id\');
                            if ($userid > 0)
                            {
                              $user = $modx->getObject(\'modUser\',array(\'active\' => true, \'id\' => $userid )); 
                              if($user->isMember(\'admins\')) {
                              echo "(Admin)";
                              }
                            }
                            echo " </span>
                            </div>
                        </div>
                    </div>
                </nav>
            </div>";
return;
';