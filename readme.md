# BlackMarket 
First BlackMarket Plugin to be out on PocketMine-MP

## Overview
BlackMarket allows you to list certain items for sale for a the price you want it to be.

![BlackMarket](https://github.com/OmChendvankar/ADVBlackMarket/blob/main/img/BlackMarket.png)

## Features
>- Hopper GUI
>- Config (Explained below)
>- Cooldown system with removecooldown option
>- Economy plugin support (EconomyAPI as of now)
>- Customizable Items and their cost.
- More Coming soon!
---
## Download
Download the plugin from [GitHub Releases](https://github.com/OmChendvankar/ADVBlackMarket)

---
## Config

<details>
    <summary>Click to open</summary>

```yaml
#Items you want to put in the BlackMarket
#you have to mention the Id,Metadata and amount of the specfic item you can refer to https://minecraft-ids.grahamedgecombe.com/
#ByDefault its Wooden, Iron and Diamond Sword as Item1,Item2 and Item3 respectively.
#Item1 Data
Item1_Id: 268
Item1_Meta: 0
Item1_Amt: 1
#Item2 Data
Item2_Id: 267
Item2_Meta: 0
Item2_Amt: 1
#Item3 Data
Item3_Id: 276
Item3_Meta: 0
Item3_Amt: 1

#Cost of the Item you put in BlackMarket
#Cost of Item1
Cost1: 10
#Cost of Item2
Cost2: 10
#Cost of Item3
Cost3: 10
```
</details>

---
## Commands
| Command        | Description           |  
| ------------- |:--------------|  
| /bm, /blackmarket | BlackMarket main command, opens the menu|   
| /rcd **[ign]** | Removes the Cooldown form the Blackmarket to buy an item for the specified player.  |  

## Contributing
You can contribute to this project by opening a PR!  

## Contributors
- [OmChendvankar](https://github.com/OmChendvankar)
- [Rushil](https://github.com/Rushil13579)

## Virions Used
-[InvMenu](https://github.com/Muqsit/InvMenu) (Muqsit)
