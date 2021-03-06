<?php

namespace ApCode\Billet;

class BilletRepository
{
    /**
     * @var StorageInterface[]
     */
    private static $storages = [];
    
    public static function addStorage(StorageInterface $storage)
    {
        self::$storages[] = $storage;
    }
    
    public static function getBilletInstance($class, $id)
    {
        $backStorages = [];
        
        if ($id) {
            foreach (self::$storages as $storage) {
                $billet = $storage->getRecord($class, $id);
                
                if ($billet) {
                    break;
                }
                
                $backStorages[] = $storage;
            }
        }
        
        if (empty($billet)) {
            return new $class;
        } else {
            if ($backStorages) {
                foreach ($backStorages as $storage) {
                    $storage->saveRecord($billet);
                }
            }
            
            return $billet;
        }
    }
    
    public static function saveBillet(BaseBillet $billet)
    {
        foreach (array_reverse(self::$storages) as $storage) {
            /* @var $storage StorageInterface */
            $storage->saveRecord($billet);
        }
    }
    
    public static function clearCache()
    {
        foreach (self::$storages as $storage) {
            $storage->clearCache();
        }
    }
}