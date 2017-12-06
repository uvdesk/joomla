    jQuery(function(){
            jQuery('body').append('<div class="content-wrap"><img class="ajax-loader-img ajax-loader" style="" src="https://cdn.uvdesk.com/uvdesk/images/aa1b406.gif" alt=""></div>');            
            callApi();
            saveResult=''; 
            jQuery('#wk_customersort').on('change',function(){
                callApi();
            });
            jQuery('.wk_selectbox #search-ticket').on('keyup',function(e){
                if(jQuery.trim(jQuery(this).val()).length){
                    callApi();
                }
                if(!jQuery.trim(jQuery(this).val()).length){
                    if(e.keyCode==8){
                        callApi();
                    }
                }
            });      
            function callApi(page=1){
                var sort=jQuery('#wk_customersort').val();
                var search=jQuery('.wk_selectbox #search-ticket').val();                
                jQuery.ajax({
                    url:"index.php?option=com_uvdeskwebkul&view=users&task=users.getCustomers",
                    data:{
                        page:page,
                        sort:sort,
                        search:search
                    },
                    dataType:'json',
                    method:'POST',
                    success: function(result){
                        result=JSON.parse(JSON.stringify(result));       
                        saveResult=result;
                        if(result!=null){
                            var pagination='';
                            var count=result.pagination_data.totalCount;
                            var pageCount=result.pagination_data.pageCount;
                            if(Object.keys(result.pagination_data.pagesInRange).length>0&&result.customers.length>0){
                                pagination='<tr><td colspan="7"><ul class = "pagination">';
                                if(result.pagination_data.current==1){
                                    pagination+='<li class="disabled"><a href = "javascript:void(0)">&laquo;</a></li>';
                                } else{
                                        pagination+='<li><a href = "javascript:void(0)">&laquo;</a></li>';
                                }
                                for(var i=0;i<Object.keys(result.pagination_data.pagesInRange).length;i++){
                                    if(result.pagination_data.current==result.pagination_data.pagesInRange[i]){
                                        pagination+='<li class="active"><a href = "javascript:void(0)">'+result.pagination_data.pagesInRange[i]+'</a></li>';
                                    } else{
                                        pagination+='<li ><a href = "javascript:void(0)">'+result.pagination_data.pagesInRange[i]+'</a></li>';
                                    }
                                }
                                if(result.pagination_data.current==pageCount){
                                    pagination+='<li class="disabled"><a href = "javascript:void(0)">&raquo;</a></li></ul></td></tr>';
                                } else {
                                    pagination+='<li ><a href = "javascript:void(0)">&raquo;</a></li></ul></td></tr>';
                                    
                                }
                            }                        
                        jQuery('#wkTabContent tfoot').html(pagination);                         
                            var star='gray';
                            var bodyString='';
                            var website='envelope-o';
                            if(Object.keys(result.customers).length>0){
                                for (var i =0; i<Object.keys(result.customers).length; i++) {
                                   if(result.customers[i].isActive){
                                        var active="success";
                                        var activeText='Active';
                                    } else {
                                        var active="danger";
                                        var activeText='Disabled';
                                    }
                                    if(result.customers[i].smallThumbnail==null){
                                        result.customers[i].smallThumbnail='https://cdn.uvdesk.com/uvdesk/images/163b0ed.png';
                                    }
                                    if(result.customers[i].isStarred!=null&&result.customers[i].isStarred){
                                        var star="mark-star-yellow";
                                    } else {
                                        var star="mark-star-gray";
                                    }
                                    
                                    bodyString+='<tr><td><span class="round-tabs two"><img class="img-responsive wkcustomer" src="'+result.customers[i].smallThumbnail+'"></span>'+result.customers[i].name+'</td><td>'+result.customers[i].email+'</td><td> '+result.customers[i].count+' Open Tickets </td><td class="text-center">'+result.customers[i].source+'</td><td><span class="badge badge-'+active+'">'+activeText+'</span></td><td class="action"><span id="'+result.customers[i].id+'" class="delete-user"><span class="badge badge-danger delete"><i class="fa fa-times"></i></span></span></td><td class="last"><a webkul="'+result.customers[i].id+'" class="mark-star '+star+" "+result.customers[i].id+'" href="javascript:void(0)"><i class="fa fa-star "></i></a></td></tr>';
                                }
                            }
                            else{
                                bodyString="<tr><td colspan='7'><p style='text-align:center' class='alert alert-info'>No Record Found</p></td></tr><tr></tr>"
                            }
                            jQuery('.wk_bodytable').empty();
                            jQuery('.wk_bodytable').append(bodyString);
                            jQuery('.wk_pagination').html(pagination);
                        } else {
                             Joomla.renderMessages({'error': [result.error] });
                        }
                    },
                    beforeSend: function(){
                        jQuery('.content-wrap').show();
                    },
                    complete: function(){
                        jQuery('.content-wrap').hide();
                    },
                    error:function(xhr, ajaxOptions, thrownError){
                    }
                });
            }
            jQuery('#wkTabContent').on('click','tfoot .pagination li a', function(){
                
                if (jQuery(this).text()=="»") {
                    if (typeof(saveResult.pagination_data)!=='undefined'&&typeof(saveResult.pagination_data.next)!=='undefined'&&saveResult.pagination_data.next!=null&&saveResult.pagination_data.pageCount>saveResult.pagination_data.current-1) {
                        callApi(saveResult.pagination_data.next);
                    }                                    
                } else if(jQuery(this).text()=="«"){
                    if (typeof(saveResult.pagination_data)!=='undefined'&&typeof(saveResult.pagination_data.previous)!=='undefined'&&saveResult.pagination_data.previous!=null&&saveResult.pagination_data.current>1) {
                        callApi(saveResult.pagination_data.previous);
                    }    
                } else if(Number.isInteger(parseInt(jQuery(this).text()))){
                    callApi(jQuery(this).text());
                }
                saveResult='';
            //}
                //
            });
            jQuery('#user-table').on('click','.delete-user',function(){
            var cid=jQuery(this).attr('id');
            swal({
                    title: "Are you sure?",
                    text: "You will not be able to recover this User!",
                    type: "warning",
                    showCancelButton: true,
                    confirmButtonColor: "#DD6B55",
                    confirmButtonText: "Yes, delete it!",
                    closeOnConfirm: false
                },
                    function(isConfirm) {
                    if (isConfirm) {
                        jQuery.ajax({
                            url:"index.php?option=com_uvdeskwebkul&view=users&task=users.deleteCustomer",
                            type:"POST",
                            data:{"customerId":cid},
                            success:function(data){
                                swal({
                                    title: 'Deleted!',
                                    text: 'Customer Deleted successfully!',
                                    type: 'success'
                                    });
                                callApi(saveResult.pagination_data.current);
                            }                       
                        });                   
                    } else {
                        //swal("Cancelled", "", "error");
                    }
                    
                });
        });
        jQuery('#user-table').on('click','.mark-star',function(){
            var cid=jQuery(this).attr('webkul');
                jQuery.ajax({
                    url:"index.php?option=com_uvdeskwebkul&view=users&task=users.starCustomer",
                    type:"POST",
                    data:{"customerId":cid},
                    success:function(data){
                        var data=JSON.parse(data);
                        if(jQuery('.'+data.customerId+' i').hasClass('mark-star-yellow')){
                            jQuery('.'+data.customerId+' i').removeClass('mark-star-yellow');
                        }else{
                                jQuery('.'+data.customerId+' i').addClass('mark-star-yellow');                                
                        }
                        swal("Successful!", data.message, data.alertClass);
                                                
                },
                beforeSend: function(){
                    jQuery('.content-wrap').show();
                },
                complete: function(){                                   
                    jQuery('.content-wrap').hide();
                }
            });                 
        });
    });