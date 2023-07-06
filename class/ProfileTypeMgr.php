<?php

/**
 * ProfileTypeMgr
 *
 * A class for managing profile types and their sub-types
 * @author      Alabi A. <alabi.adebayo@alabiansolutions.com>
 * @copyright   Alabian Solutions Limited
 * @version 	1.0 => October 2022
 * @link        alabiansolutions.com
*/

class ProfileTypeMgrExpection extends Exception
{
}

class ProfileTypeMgr
{
    /** @var TblProfileType an instance of TblProfileType  */
    protected TblProfileType $tblProfileType;

    /** @var string path to setting.json  */
    protected string $path;


    /**
     * instantiation of ProfileTypeMgr
     *
     * @param string path the path to setting.json
     */
    public function __construct(string $path = SETTING_FILE)
    {
        $DbConnect = DbConnect::getInstance(SETTING_FILE);
        $this->tblProfileType = new TblProfileType();
        $this->setPath($path);
    }

    /**
     * get the path to the setting.json file
     *
     * @return string path to setting.json file
     */
    public function getPath():string
    {
        return $this->path;
    }

    /**
     * set the path to the setting.json file
     *
     * @param string path the path to the setting.json file
     */
    public function setPath(string $path)
    {
        if (!file_exists($path)) {
            throw new ProfileTypeMgrExpection("setting file missing");
        }
        $this->path = $path;
    }

    /**
     * for retrieving a profile type information
     *
     * @param int $id the profile type id
     * @return array info of the profile type
    */
    public function getInfo(int $id):array
    {
        $info = $this->tblProfileType->get($id);
        return $info;
    }
    
    /**
     * get all the profile types and sub types
     *
     * @param bool includeSubType if the profile sub type should be included in returned
     * @return array collection of profile types and sub types [type1=>[subType1, subType2,...],...] or [type1, type2...]
     */
    public function getAllProfileTypes(bool $includeSubType = true):array
    {
        $allSubs = [];
        $Settings = new Settings($this->path, false);
        
        if ($includeSubType) {
            foreach ($Settings->getDetails()['profileType'] as $type => $aSubType) {
                $allSubs[$type] = $aSubType;
            }
        } else {
            foreach ($Settings->getDetails()['profileType'] as $type => $aSubType) {
                $allSubs[] = $type;
            }
        }
        return $allSubs;
    }

    /**
     * get all the profile types id
     *
     * @return array collection of profile types their id [type1=>id1, type2=>id2...]
     */
    public function getAllProfileTypeIds():array
    {
        $types = [];
        if ($result = $this->tblProfileType->select([TblProfileType::ID, TblProfileType::NAME])) {
            foreach ($result as $aResult) {
                $types[$aResult[TblProfileType::NAME]] = $aResult[TblProfileType::ID];
            }
        }
        return $types;
    }
     
    /**
     * creation of a new profile type
     *
     * @param string type the new profile type
     * @return bool true if the profile type was created successfully
     */
    public function createProfileType(string $type):bool
    {
        $created = false;
        $Settings = new Settings($this->path, false);
        $settingInfo = $Settings->getAllDetails();
        try {
            $this->tblProfileType->insert([TblProfileType::NAME => [$type, 'isValue']]);
            //implement the creation of this profile table
            $created =true;
        } catch (\Throwable $th) {
            $created = false;
        }
        
        if ($created) {
            $settingInfo['profileType'][$type] = [];
            $handle = fopen($this->path, "w");
            fwrite($handle, json_encode($settingInfo));
            fclose($handle);
        }
        return $created;
    }

    /**
     * deletion of a profile type
     *
     * @param string type the profile type to be deleted
     */
    public function deleteProfileType(string $type)
    {
        $Settings = new Settings($this->path, false);
        $settingInfo = $Settings->getAllDetails();
        if ($this->tblProfileType->select([], [TblProfileType::NAME => ['=', $type, 'isValue']])) {
            unset($settingInfo['profileType'][$type]);
            $handle = fopen($this->path, "w");
            fwrite($handle, json_encode($settingInfo));
            fclose($handle);
            $this->tblProfileType->delete([TblProfileType::NAME => ['=', $type, 'isValue']]);
        } else {
            throw new ProfileTypeMgrExpection("invalid profile type");
        }
    }

    /**
     * creation of a new sub profile type
     *
     * @param string type the profile type
     * @param string subType the new profile sub type
     * @return bool true if the profile type was created successfully
     */
    public function createSubProfileType(string $type, string $subType):bool
    {
        $created = false;
        $Settings = new Settings($this->path, false);
        $settingInfo = $Settings->getAllDetails();

        if ($typeInfo = $this->tblProfileType->select([], [TblProfileType::NAME => ['=', $type, 'isValue']])) {
            if (array_search($subType, $settingInfo['profileType'][$type]) === false) {
                $handle = fopen($this->path, "w");
                $settingInfo['profileType'][$type][] = $subType;
                fwrite($handle, json_encode($settingInfo));
                fclose($handle);
                
                $subs = $typeInfo[0][TblProfileType::SUBS] ? json_decode($typeInfo[0][TblProfileType::SUBS], true) : [];
                $subs[] = $subType;
                $this->tblProfileType->update([TblProfileType::SUBS=>[$subs, 'isValue']], [TblProfileType::NAME => ['=', $type, 'isValue']]);
                $created = false;
            } else {
                throw new ProfileTypeMgrExpection("Profile Type '$type' already have sub type '$subType'");
            }
        } else {
            throw new ProfileTypeMgrExpection("invalid profile type");
        }
        
        return $created;
    }

    /**
     * deletion of a sub profile type
     *
     * @param string type the profile type
     * @param string subType the profile sub type
     */
    public function deleteSubProfileType(string $type, string $subType)
    {
        $created = false;
        $Settings = new Settings($this->path, false);
        $settingInfo = $Settings->getAllDetails();

        if ($typeInfo = $this->tblProfileType->select([], [TblProfileType::NAME => ['=', $type, 'isValue']])) {
            if (($index = array_search($subType, $settingInfo['profileType'][$type])) !== false) {
                $handle = fopen($this->path, "w");
                unset($settingInfo['profileType'][$type][$index]);
                fwrite($handle, json_encode($settingInfo));
                fclose($handle);
                
                $subs = $typeInfo[0][TblProfileType::SUBS];
                $subs = json_decode($subs, true);
                $index = array_search($subType, $subs);
                unset($subs[$index]);
                $this->tblProfileType->update([TblProfileType::SUBS=>[$subs, 'isValue']], [TblProfileType::NAME => ['=', $type, 'isValue']]);
                $created = false;
            } else {
                throw new ProfileTypeMgrExpection("Profile Type '$type' does not have sub type '$subType'");
            }
        } else {
            throw new ProfileTypeMgrExpection("invalid profile type");
        }
    }
}
