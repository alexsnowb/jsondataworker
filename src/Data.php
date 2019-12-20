<?php


namespace Snowb\DataWorker;


class Data
{
    public $dataJsonTemplate = '{
      "action": [],
      "name": "IMPORT",
      "level": "1",
      "isLeaf": true,
      "expanded": false,
      "loaded": true
    }';

    public $itemJsonTemplate = '[{
            "msgType": "RecordedEvent",
            "data": "/itemdescription",
            "evType": "redirect",
            "newValue": ""
            },
            {
            "msgType": "RecordedEvent",
            "data": "/itemdescription/edit/item_description_id/#####DATACOLUMN1#####",
            "evType": "redirect",
            "newValue": ""
            },
            {
            "msgType": "RecordedEvent",
            "data": "#item_description-sku",
            "evType": "change",
            "newValue": "#####DATACOLUMN2#####"
            },
            {
            "msgType": "RecordedEvent",
            "data": "#save-btn",
            "evType": "click",
            "newValue": ""
            },
            {
            "data": "",
            "evType": "timer",
            "msgType": "userEvent",
            "newValue": "5000"
            }]';
}