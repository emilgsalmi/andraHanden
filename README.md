
------------------------API FOR THE SECONDHAND STORE ANDRAHANDEN----------------------------
            THIS IS A PHP CODE THAT SERVES A BASIC ROUTER FOR A WEB APPLICATION
                    IT HANDLES VARIOUS HTTP METHODS AND ROUTES REQUESTS


---FOR SELLERS---

TO GET ALL THE SELLERS:

localhost/andraHanden/sellers

TO GET A SELLER BY ID:

localhost/andraHanden/sellers/{id}

TO POST A NEW SELLER:

localhost/andraHanden/sellers 
then insert into body the following
{
    "seller_name":"Name"
}

---FOR ITEMS---

TO GET ALL ITEMS:

localhost/andraHanden/items

TO GET AN ITEM BY ID:

localhost/andraHanden/items/{id}

TO POST A NEW ITEM:

localhost/andraHanden/items/{id}

then insert into body the following
{
    "item_name":"Item Name",
    "seller_id":"1",    <- (The correct Seller Id)
    "sale_amount":"100"
}

TO UPDATE ITEM AS SOLD 

PUT localhost/andraHanden/items/{id} 

The ID in the URL needs to be the same as the ItemId otherwise it will not work

then insert into body the following
{
    "seller_id": 1, <- (The correct Seller Id)
    "sold": 1 <- (The standard value of "sold" is null so it needs a 1 for it to marked as sold)
}

THANK YOU!

