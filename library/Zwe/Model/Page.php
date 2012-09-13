<?php

class Zwe_Model_Page extends Zwe_Model_Tree
{
    protected $_dependentTables = array('Zwe_Model_Page');

    protected $_referenceMap = array(
        'ParentPage' => array(
            'columns' => 'IDParent',
            'refTableClass' => 'Zwe_Model_Page',
            'refColumns' => 'IDPage',
            Zend_Db_Table_Abstract::ON_DELETE => Zend_Db_Table_Abstract::CASCADE,
            Zend_Db_Table_Abstract::ON_UPDATE => Zend_Db_Table_Abstract::CASCADE
        )
    );

    /**
     * @var Zwe_Db_Table_Row_Page
     */
    protected static $_thisPage = null;

    public static function getThisPage()
    {
        return static::$_thisPage;
    }

    public static function setThisPage(Zwe_Db_Table_Row_Page $page)
    {
        static::$_thisPage = $page;
    }

    public static function rebuildNavigation($IDParent = 0, $navigationString = '', $commentString = '')
    {
        $navigationIni = APPLICATION_PATH . '/configs/navigation/application.ini';

        if(0 == $IDParent) {
            file_put_contents($navigationIni, "[production]\n\n");
            file_put_contents($navigationIni, "; Home\n", FILE_APPEND);
            file_put_contents($navigationIni, "navigation.home.label = Home\n", FILE_APPEND);
            file_put_contents($navigationIni, "navigation.home.module = default\n", FILE_APPEND);
            file_put_contents($navigationIni, "navigation.home.controller = index\n", FILE_APPEND);
            file_put_contents($navigationIni, "navigation.home.action = index\n", FILE_APPEND);
            file_put_contents($navigationIni, "navigation.home.route = default\n", FILE_APPEND);
            file_put_contents($navigationIni, "navigation.home.order = -100\n", FILE_APPEND);
            $navigationString = 'navigation';
            $commentString = '; ';
        }

        $pages = static::findByIDParent($IDParent, Zwe_Model_Tree::ORDER_KEY);

        foreach($pages as $page) {
            $pageNavigationString = $navigationString . '.' . $page->Url;
            if(0 == $IDParent) {
                file_put_contents($navigationIni, "\n", FILE_APPEND);
            }
            file_put_contents($navigationIni, $commentString . $page->Title . "\n", FILE_APPEND);
            file_put_contents($navigationIni, $pageNavigationString . ".label = " . $page->Title . "\n", FILE_APPEND);
            file_put_contents($navigationIni, $pageNavigationString . ".params.idPage = " . $page->IDPage . "\n", FILE_APPEND);
            file_put_contents($navigationIni, $pageNavigationString . ".route = db\n", FILE_APPEND);
            file_put_contents($navigationIni, $pageNavigationString . ".order = " . $page->{Zwe_Model_Tree::ORDER_KEY} . "\n", FILE_APPEND);

            static::rebuildNavigation($page->IDPage, $pageNavigationString . '.pages', $commentString . $page->Title . ' > ');
        }
    }
}