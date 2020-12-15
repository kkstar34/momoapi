<?php
/**
 * User: Kouyate Karim
 * Date: 15/12/2020
 * Time: 09:04
 */

namespace Kouyatekarim\Momoapi\Traits;


trait Configurations
{
    /**
     * Create new object
     *
     * @param $options
     * @return static
     */

    //tr


    public static function create($options) {
        return new static($options);
    }

    /**
     * Set object options
     *
     * @param $options
     */
    protected function setOptions($options) {
        foreach ($options as $option => $value) {
            try{
                $this->{$option} = $value;
            }catch (\Exception $exception) {}
        }
    }
}