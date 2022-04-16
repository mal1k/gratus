<?php

use App\Models\organizationgroups;

function organizationgroups($org_id)
    {

    return $groups = organizationgroups::where('org_id', '=', "$org_id")
            ->limit(15)
            ->get();
    }
