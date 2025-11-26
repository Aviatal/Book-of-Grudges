import {Weapon} from "./Weapon";
import {Armor} from "./Armor";
import {Ammunition} from "./Ammunition";
import {CommonItem} from "./CommonItem";

export interface MarketplaceItem {
    id: number,
    tradeable_id: number,
    tradeable_type: string,
    tradeable: Weapon|Armor|Ammunition|CommonItem
}
