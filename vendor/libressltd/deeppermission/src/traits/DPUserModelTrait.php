<?php

namespace LIBRESSLtd\DeepPermission\Traits;

use Auth;
use App\Models\Permission;
use App\Models\Role;

trait DPUserModelTrait {
    public $__localPermissions = NULL;
	public $__localRoles = NULL; 
	
    public function roles()
    {
        return $this->belongsToMany('App\Models\Role', "user_roles", "user_id", "role_id");
    }
	
    public function permissions()
    {
        return $this->belongsToMany('App\Models\Permission', "user_permissions", "user_id", "permission_id");
    }
	
	public function loadAllPermissionAndRole()
	{
		if ($this->__localRoles == NULL)
		{
			$this->__localRoles = $this->roles()->with("permissions")->get();
		}
		
		if ($this->__localPermissions == NULL);
		{
			$this->__localPermissions = array();
			foreach ($this->permissions as $permission)
			{
				$this->__localPermissions[] = $permission;
			}
			foreach ($this->__localRoles as $role)
			{
				foreach ($role->permissions as $permission)
				{
					$found = FALSE;
					foreach ($this->__localPermissions as $p)
					{
						if ($p->id == $permission->id)
						{
							$found = TRUE;
							break;
						}
					}
					if (!$found)
					{
						$this->__localPermissions[] = $permission;
					}
				}
			}
		}
	}
	
	public function allPermission()
	{
		$this->loadAllPermissionAndRole();
		return $this->__localPermissions;
	}
	
	public function hasRole($role_code)
	{
		if (config("deeppermission.autofill"))
		{
			Role::addIfNotExist(false, $role_code);
		}
		if ($this->id == env("LIBRE_DP_ADMIN_ID", -1))
		{
			return TRUE;
		}
		$this->loadAllPermissionAndRole();
		foreach ($this->__localRoles as $role)
		{
			if ($role_code === $role->code)
			{
				return TRUE;
			}
		}
		return FALSE;
	}
	
	public function hasPermission($permission_code)
	{
		if (config("deeppermission.autofill"))
		{
			Permission::addIfNotExist(false, $permission_code);
		}
		if ($this->id == env("LIBRE_DP_ADMIN_ID", -1))
		{
			return TRUE;
		}
		$this->loadAllPermissionAndRole();
		foreach ($this->__localPermissions as $permission)
		{
			if ($permission_code === $permission->code)
			{
				return TRUE;
			}
		}
		return FALSE;
	}

    public function scopeWithRole($query, $role_code)
    {
		if (config("deeppermission.autofill"))
		{
			Role::addIfNotExist(false, $role_code);
		}
        return $this->whereHas('roles', function ($query) use ($role_code) {
            $query->where('code', '=', $role_code);
        });
    }

    public function scopeWithPermission($query, $permission_code)
    {
		if (config("deeppermission.autofill"))
		{
			Permission::addIfNotExist(false, $permission_code);
		}
        return $this->whereHas('permissions', function ($query) use ($permission_code) {
            $query->where('code', '=', $permission_code);
        })->orWhereHas('roles', function ($query) use ($permission_code) {
            $query->whereHas("permissions", function ($query) use ($permission_code) {
                $query->where('code', '=', $permission_code);
            });
        });
    }
}