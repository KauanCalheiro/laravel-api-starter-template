<?php

namespace App\Enums;

enum PermissionEnum: string
{
    case CREATE_USER = 'user.create';
    case READ_USER   = 'user.read';
    case UPDATE_USER = 'user.update';
    case DELETE_USER = 'user.delete';

    case CREATE_COUNTRY = 'country.create';
    case READ_COUNTRY   = 'country.read';
    case UPDATE_COUNTRY = 'country.update';
    case DELETE_COUNTRY = 'country.delete';

    case CREATE_STATE = 'state.create';
    case READ_STATE   = 'state.read';
    case UPDATE_STATE = 'state.update';
    case DELETE_STATE = 'state.delete';

    case CREATE_CITY = 'city.create';
    case READ_CITY   = 'city.read';
    case UPDATE_CITY = 'city.update';
    case DELETE_CITY = 'city.delete';
}
