<?php
/**
 * Created by IntelliJ IDEA.
 * User: clong
 * Date: 18-7-1
 * Time: 上午1:45
 */

namespace main\model;


interface Regional
{
    public function province();
    public function city($id);
    public function area($id);
}