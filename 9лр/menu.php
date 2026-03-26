<?php
// модуль menu.php
// формирует главное меню и подменю для просмотра

function getMenu() {
    // определяем текущий пункт меню
    if (!isset($_GET['p']) || !in_array($_GET['p'], ['viewer', 'add', 'edit', 'delete'])) {
        $current = 'viewer';
    } else {
        $current = $_GET['p'];
    }
    
    $html = '<div id="menu">';
    
    // пункт "Просмотр"
    $html .= '<a href="/?p=viewer"';
    if ($current == 'viewer') $html .= ' class="selected"';
    $html .= '>Просмотр</a>';
    
    // пункт "Добавление записи"
    $html .= '<a href="/?p=add"';
    if ($current == 'add') $html .= ' class="selected"';
    $html .= '>Добавление записи</a>';
    
    // пункт "Редактирование записи"
    $html .= '<a href="/?p=edit"';
    if ($current == 'edit') $html .= ' class="selected"';
    $html .= '>Редактирование записи</a>';
    
    // пункт "Удаление записи"
    $html .= '<a href="/?p=delete"';
    if ($current == 'delete') $html .= ' class="selected"';
    $html .= '>Удаление записи</a>';
    
    $html .= '</div>';
    
    // подменю для просмотра (сортировка)
    if ($current == 'viewer') {
        $html .= '<div id="submenu">';
        
        // определяем текущую сортировку
        $currentSort = isset($_GET['sort']) ? $_GET['sort'] : 'byid';
        
        // по умолчанию (по id)
        $html .= '<a href="/?p=viewer&sort=byid"';
        if ($currentSort == 'byid') $html .= ' class="selected"';
        $html .= '>По умолчанию</a>';
        
        // по фамилии
        $html .= '<a href="/?p=viewer&sort=fam"';
        if ($currentSort == 'fam') $html .= ' class="selected"';
        $html .= '>По фамилии</a>';
        
        // по дате рождения
        $html .= '<a href="/?p=viewer&sort=birth"';
        if ($currentSort == 'birth') $html .= ' class="selected"';
        $html .= '>По дате рождения</a>';
        
        $html .= '</div>';
    }
    
    return $html;
}
?>