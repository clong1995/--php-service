<?php
/**
 * Created by IntelliJ IDEA.
 * User: clong
 * Date: 18-6-24
 * Time: 上午6:57
 */

namespace db;

interface Company extends Base
{
    public function getAll();
    public function getById($id);
    public function has($id,$license);
}