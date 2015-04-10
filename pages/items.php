<script type="text/javascript">
$(document).ready(function () {

var source =
{
    datatype: "json",
    cache: false,
    datafields: [
        { name: 'item_idp'},//item_id for the lastInserted
        { name: 'i_id'},//item id
        { name: 'i_name'},//item name
        { name: 'i_serial'},//serial
        { name: 'i_model'},//model
        { name: 'i_qty'},//qunatity
        { name: 'i_dob'},//date of purchase
        { name: 'ee_name'},//employee name
        { name: 'i_remarks'},//remarks
        { name: 'i_item_description'},//descrioption
        { name: 'i_price'},
        { name: 'ca_name'},//categoryname
        { name: 'co_name'}//company_name
],
id: 'i_id',
url: 'items-controller.php',
addrow: function (rowid, rowdata, position, commit) {
    // synchronize with the server - send insert command
    var data = "insert=true&;" + $.param(rowdata);
        $.ajax({
            dataType: 'json', 
            url: 'items-controller.php',
            type: "GET", 
            data: data,
            cache: false,
            success: function (data, status, xhr) {
                // insert command is executed. Recieve the id
                commit(true,data.item_idp);                               
            },
            error: function(jqXHR, textStatus, errorThrown)
            {
                commit(false);
                alert('error '+errorThrown);
            }
        });                         
},
deleterow: function (rowid, commit) {
          var data = "delete=true&" + $.param({e_id: rowid});
                       $.ajax({
                            dataType: 'json',
                            url: 'controller.php',
                            cache: false,
                            data: data,
                            success: function (data, status, xhr) {
                               // delete command is executed.
                               commit(true);
                                      
                            },
                            error: function(jqXHR, textStatus, errorThrown)
                            {
                                commit(false);
                            }
                        });                                             
},


updaterow: function (rowid, rowdata, commit) {
var data = "update=true&;" + $.param(rowdata);
                    $.ajax({
                        dataType: 'json',
                        url: 'items-controller.php',
                        data: data,
                        success: function (data, status, xhr) {
                            // update command is executed.
                            commit(true);
                        }
                    });   
}};


var dataAdapter = new $.jqx.dataAdapter(source);
var editrow = -1;
var ifname ='';
var iid='';

    var date = new Date();
        date.setFullYear(1985, 0, 1);
      
   $('#birthInput1').jqxDateTimeInput({ width: 150, height: 22, value: $.jqx._jqxDateTimeInput.getDateTime(date) });

   $("#jq").jqxGrid(
   {           
                editable: true,
                //editmode: false,
                width: 1400,
                height: 900,
                showfilterrow: true,
                filterable: true,
                sortable: true,
                selectionmode: 'checkbox',//'singlecell'
                pageable: true,
                pagermode:'simple',
                autoheight: true,
                columnsresize: true,
                source: dataAdapter,
                showtoolbar: true,
        rendertoolbar: function (toolbar) {
                  var me = this;
                    var container = $("<div style='margin: 5px;'></div>");
                    toolbar.append(container);
                    container.append('<input style="margin-left: 5px;" id="additem" type="button" value="Add New Item" />');
                    container.append('<input style="margin-left: 5px;" id="deleteitem" type="button" value="Delete Selected Item" />');
                    container.append('<input style="margin-left: 5px;" id="ref" type="button" value="Refresh Grid" />');
                    $("#additem").jqxButton();
                    $("#deleteitem").jqxButton();
                    $("#ref").jqxButton();

                 
                    $("#additem").on('click', function () {
                      $("#popupadd").jqxWindow('open');
                    });
                    $("#deleteitem").on('click', function () {
                      alert("delete");
                     });
                    $("#ref").on('click', function () {
                     $("#jq").jqxGrid('updatebounddata', 'cells');
                     });
      },
          
            columns: [
           
                    { text: 'Items Id', datafield: 'i_id', editable: false,width: 100 },
                    { text: 'Item Name', datafield: 'i_name', width: 100  },
                    { text: 'Serial', datafield: 'i_serial', width: 100 },
                    { text: 'Model', datafield: 'i_model', width: 100 },
                    { text: 'Price', datafield: 'i_price', width:50 },
                    { text: 'Quantity', datafield: 'i_qty', width:50 },
                    { text: 'Date of Birth', datafield: 'i_dob', filtertype: 'date',columntype: 'datetimeinput', width:85, align: 'right', cellsalign: 'right', cellsformat: 'dddd-MMMM-yyyy'
                    },
                    { text: 'Recieved by', datafield: 'ee_name', columntype: 'dropdownlist', width: 125,
                        createeditor: function (row, value, editor) {
                            editor.jqxDropDownList({ source: countriesAdapter, displayMember: 'label', valueMember: 'value' });
                        }
                    },
                   { text: 'Category', datafield: 'co_name',filtertype: 'checkedlist',    columntype: 'dropdownlist', width: 125,
                        createeditor: function (row, value, editor) {
                            editor.jqxDropDownList({ source: countriesAdapter, displayMember: 'label', valueMember: 'value' });
                        }
                    },
                    { text: 'Remarks', datafield: 'i_remarks', width: 180 },
                    { text: 'description', datafield: 'i_item_description', width: 100 },
                    { text: 'Company', datafield: 'ca_name',filtertype: 'checkedlist',    columntype: 'dropdownlist', width: 125,
                        createeditor: function (row, value, editor) {
                            editor.jqxDropDownList({ source: countriesAdapter, displayMember: 'label', valueMember: 'value' });
                        }
                    },
                    { text: 'Edit', datafield: 'Edit', columntype: 'button', cellsrenderer: function () {
                     return "Edit";
                      }, buttonclick: function (row) {
                       // open the popup window when the user clicks a button.
                         editrow = row;
                     var offset = $("#jqxgrid").offset();
                     $("#popupadd").jqxWindow({ position: { x: parseInt(offset.left) + 60, y: parseInt(offset.top) + 60 } });

                     // get the clicked row's data and initialize the input fields.
                     var dataRecord = $("#jq").jqxGrid('getrowdata', editrow);
                     $("#itemname").val(dataRecord.i_name);
                     $("#itemid").val(dataRecord.i_id);
                     $("#itemserial").val(dataRecord.i_serial);
                     $("#itemmodel").val(dataRecord.i_model);
                     $("#itememployee").val(dataRecord.ee_name);
                     $("#itemqty").val(dataRecord.i_qty);
                    $("#birthInput1").val(dataRecord.i_dob);
                    $("#itemcompany").val(dataRecord.ca_name);
                    $("#itemremarks").val(dataRecord.i_remarks);
                    $("#itemdescription").val(dataRecord.i_item_description);
                    $("#itemcategory").val(dataRecord.co_name);
                     // show the popup window.
                        ifname=dataRecord.i_name;
                        iid=dataRecord.i_id;


                              //  alert(iid+' '+ifname); 
                     $("#popupadd").jqxWindow('open');
                     }
                   }]
        });//end of jqxgrid
      $("#popupadd").jqxWindow({
                      width: 800, height:500,resizable: false,  isModal: true, autoOpen: false, cancelButton: $("#Cancel"), modalOpacity: 0.01           
        });



    
        // prepare the data
        var source =
        {
            datatype: "json",
            datafields: [
            { name: 'allid'},
            { name: 'fullname'},
            ],
            url: 'comboboxdata.php',
            async: false
        };
         var companysource =
        {
            datatype: "json",
            datafields: [
            { name: 'cid'},
            { name: 'cname'},
            ],
            url: 'comboboxdata-company.php',
            async: false
        };
        var categorysource =
        {
            datatype: "json",
            datafields: [
            { name: 'catid'},
            { name: 'catname'},
            ],
            url: 'comboboxdata-category.php',
            async: false
        };
        var dataAdapter = new $.jqx.dataAdapter(source);
        var dataAdapterCompany = new $.jqx.dataAdapter(companysource);
        var dataAdapterCategory = new $.jqx.dataAdapter(categorysource); 

        var generaterow = function (id) {//adding new row for new data entry
                var row = {};

                row["item_idp"] = "";              
                row["item_name"] = $("#itemname").val();
                row["item_serial"] = $("#itemserial").val();
                row["item_model"] = $("#itemmodel").val();
                row["item_qty"] =  $("#itemqty").val();
                row["item_dop"] = $("#birthInput1").val();
                row["item_employee"] = $("#itememployee").val();
                row["item_remarks"] = $("#itemremarks").val();
                row["item_description"] = $("#itemdescription").val();
                row["item_category"] = $("#itemcategory").val();
                row["item_company"] =$("#itemcompany").val();
                row["item_price"] =$("#itemprice").val();
                      
                return row;
            }

           var tobeupdate = function (id) {//adding new row for new dsata entry
                var row = {};

                row["item_idp"] = iid;            
                row["item_name"] = ifname;
           
                      
                return row;
            }
       
        $("#itememployee").jqxComboBox(
        {
            source: dataAdapter,
            
            width: 169,
            height: 25,
            selectedIndex: 0,
            promptText: "Select customer",
            displayMember: 'fullname',
            valueMember: 'allid'
        }); 

          $("#itemcompany").jqxComboBox(
        {
            source: dataAdapterCompany,
            
            width: 169,
            height: 25,
            selectedIndex: 0,
            promptText: "Select customer",
            displayMember: 'cname',
            valueMember: 'cid'
        }); 

         $("#itemcategory").jqxComboBox(
        {
            source: dataAdapterCategory,
            
            width: 169,
            height: 25,
            selectedIndex: 0,
            promptText: "Select customer",
            displayMember: 'catname',
            valueMember: 'catid'
        });  

  

         $("#Save").click(function () {


 
     var datarow = generaterow();

     var commit = $("#jq").jqxGrid('addrow', null, datarow,'first');
       
                     $("#popupadd").jqxWindow('hide');
                     $('#itemname').val('');
                     $('#nname').val('');
                     $('#nserial').val('');
                     $('#nmodel').val('');
                     $('#nqty').val('');
                     $('#nprice').val('');
                     $('#nremarks').val('');
                     $('#ndesc').val('');
                     
            });//end of save function
 $("#Update").on('click', function () {
      // var row = { 'item_name': $("#itemname").val(), 'item_idp': $("#itemid").val()};
 /*var datarow = tobeupdate();

     var commit = $("#jq").jqxGrid('updaterow',null,datarow);*/
        alert(iid+' '+ifname); 
$("#popupadd").jqxWindow('hide');

    });//end of update function

$("#nname").jqxInput({placeHolder: "Item", height: 25, width: 200, minLength: 1});
$("#nserial").jqxInput({placeHolder: "Serial", height: 25, width: 200, minLength: 1});
$("#nmodel").jqxInput({placeHolder: "Model", height: 25, width: 200, minLength: 1});
$("#nqty").jqxInput({placeHolder: "Quantity", height: 25, width: 200, minLength: 1});
$("#nprice").jqxInput({placeHolder: "Price", height: 25, width: 200, minLength: 1});
$("#nremarks").jqxInput({placeHolder: "Remarks", height: 25, width: 200, minLength: 1});
$("#ndesc").jqxInput({placeHolder: "Description", height: 100, width: 200, minLength: 1});
$("#nid").jqxInput({placeHolder: "Item ID", height: 25, width: 200, minLength: 1});

//$('#jqxFileUpload').jqxFileUpload({browseTemplate: 'success', uploadTemplate: 'primary',  cancelTemplate: 'danger', width: 300, uploadUrl: 'imageUpload.php', fileInputName: 'fileToUpload' });
    
});

</script>


   <html>
  <body>

      
            <div id="jq">
            </div>

  

    
<div id="popupadd">
            <div>Edit</div>
            <div style="overflow: hidden;">
              <table>
                    <tr>
                        <td align="right"></td>
                        <td align="left"><div id="nname"><input id="itemname" name="item_name" /></div></td>
                        <td align="left"><div id="nid"><input id="itemid" name="item_id" /></div></td>

                    
                    
                        <td align="right"></td>
                        <td align="left"><div id="nserial"><input id="itemserial" name="item_serial" /></div></td>
                    </tr>
                    <tr>
                        <td align="right">Model:</td>
                        <td align="left"><div id="nmodel"><input id="itemmodel" name="item_model" /></div></td>
                    
                        <td align="right">Quantity:</td>
                        <td align="left"><div id="nqty"><input id="itemqty" name="item_qty"/></div></td>
                    </tr>
                    <tr>
                        <td align="right">Price:</td>
                        <td align="left"><div id="nprice"><input id="itemprice" name="item_price"/></div></td>
                    
                        <td align="right">Date:</td>
                        <td><div name="item_dop" id="birthInput1"></div></td>
                    </tr>
                     <tr>
                    
                        <td align="right">For Employee:</td>
                        <td align="left"><div name="item_employee" id="itememployee" style="width:169 px;"></div></td>

                   
                        <td align="right">For Company:</td>
                        <td><div name="item_company" id="itemcompany" style="width:169 px;"></div></td>
                    </tr>
                     <tr>
                        <td align="right">Remakrs:</td>
                        <td align="left"><div id="nremarks"><input id="itemremarks" name="item_remarks"/></div></td>
                    
                        <td align="right">Descriptions:</td>
                        <td><div id="ndesc"><input id="itemdescription"  name="item_description"/></div></td>
                    </tr>     
                    <tr>
                        <td align="right">Image:</td>
                        <td align="left"><input type="file" id="imageupload" name="image"></div></td>
                    
                        <td align="right">Category:</td>
                        <td><div name="item_category" id="itemcategory" style="width:169 px;"></div></td>
                    </tr>               
                    <tr>
                        <td align="right"></td>
                        <td style="padding-top: 10px;" align="right"><input style="margin-right: 5px;" type="button" id="Update" value="Update" name="update" /><input style="margin-right: 5px;" type="button" id="Save" value="Save" name="insert" /><input id="Cancel" type="button" value="Cancel" /></td>
                    </tr>
                </table>
            </div>

</div>
</body>
</html>