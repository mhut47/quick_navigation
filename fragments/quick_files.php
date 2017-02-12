<?php
$drophistory = $date = $link = $where = '';
if (rex::getUser()->hasPerm('quick_navigation[history]')) {
            
            if (rex::getUser()->hasPerm('quick_navigation[own_articles]') && !rex::getUser()->isAdmin()) {
        	
        	    $where = 'WHERE updateuser="'.rex::getUser()->getValue('login').'"';
        		
        	}
            
            $qry = 'SELECT id, parent_id, clang_id, startarticle, name, updateuser, updatedate
                    FROM ' . rex::getTable('article') . ' 
                    '. $where .' 
                    ORDER BY updatedate DESC
                    LIMIT 15';
            $datas = rex_sql::factory()->getArray($qry);

            if (!count($datas)) {
			   $link .= '<li class="alert">'.rex_i18n::msg('quick_navigation_no_entries').'</li>';
			}
            
            
            if (count($datas)) {

                foreach ($datas as $data) {
                    $lang = rex_clang::get($data['clang_id']);
                    $langcode = $lang->getCode();
                    if ($langcode) {
                        $langcode = '<i class="fa fa-flag-o" aria-hidden="true"></i> ' . $langcode . ' - ';
                    }
                   $date = rex_formatter::strftime(strtotime($data['updatedate']), 'datetime');
                   $attributes = [
                        'href' => rex_url::backendPage('content/edit',
                            [
                                'mode' => 'edit',
                                'clang' => $data['clang_id'],
                                'article_id' => $data['id']
                            ]
                        )
                    ];
                    $link .= '<li><a ' . rex_string::buildAttributes($attributes) . ' title="' . $data['name'] . '">' . $data['name'] . '<br /><small>' . $langcode . '<i class="fa fa-user" aria-hidden="true"></i> ' . $data['updateuser'] . ' - ' . $date . '</small></a></li>';
                }
            }
?>
            
                <div class="btn-group">
                    <button class="btn btn-default dropdown-toggle" type="button" data-toggle="dropdown">
                        <i class="fa fa-clock-o" aria-hidden="true"></i>
                        <span class="caret"></span>
                    </button>
                    <ul class="quicknavi dropdown-menu dropdown-menu-right">
                        <?= $link ?>
                    </ul>
                </div>
<?php                 
        }
        
 ?>