import DATE_FORMAT from "@scripts/data/constants/DATE_FORMAT";
import DayJs from "dayjs";
import UserRoles from "@scripts/data/UserRoles";
import User from "@scripts/models/User";

export default class Note {
    id = null;
    text = null;
    active = false;
    title = null;
    type = null;
    created_at = null;
    user_role = null;
    leads = null;
    plans = null;

    constructor(
        {
            id = null,
            text = null,
            active = false,
            title = null,
            type = null,
            created_at = null,
            user_role = null,
            leads = null,
            plans = null
        }
    ) {
        this.id = id;
        this.text = text;
        this.active = active;
        this.type = this.mapType(type);
        this.created_at = new DayJs(created_at).format(DATE_FORMAT.NOTE_TIME);
        this.user_role = this.mapRole(user_role);
        this.title = this.mapTitle(type,title, this.user_role);
        this.leads = leads;
        this.plans = plans
    }

    mapType(type)
    {
        switch (type)
        {
            case 'escalated':
                return 'Escalated';
                break;

            case 'confirmed_connection':
                return 'Confirmed Connection';
                break;
            case 'close_connection':
                return 'Close Connection';
                break;
            case 'regular':
                return 'Regular';
                break;

            case 'submitted_connection':
                return 'submitted_connection';
                break;

            case 'invalid_property_me_note':
                return 'invalid_property_me_note';
                break;

            default:
                break;
        }
    }

    mapRole(role)
    {
        return UserRoles.HOOD.find(dt=> dt.value == role)?.text;
    }
    mapTitle(type,title, role)
    {
        if(type.toLowerCase() == 'regular')
        {
            return title + '['+role+']';
        } else if (type.toLowerCase() == 'close_connection')
        {
             return title + ' ' + role ;
        } else if (type.toLowerCase() == 'invalid_property_me_note')
        {
             return "Inserting ID details, didn't exactly match.";
        }
    }
}
