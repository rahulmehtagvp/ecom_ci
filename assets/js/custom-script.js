jQuery(document).ready(function () {
    jQuery('<div class="overlay"></div>').insertBefore(".content-wrapper");
});

function setImg(input, id) {
    if (input.files && input.files[0]) {
        var reader = new FileReader();

        reader.onload = function (e) {
            $('#' + id).attr('src', e.target.result);
        };
        reader.readAsDataURL(input.files[0]);
    }
}

function setMultiImg(input, thisObj) {
    if (input.files && input.files[0]) {
        var reader = new FileReader();

        reader.onload = function (e) {
            var count = thisObj.attr('count');
            thisObj.attr('count', count + 1);
            jQuery('[id="multipleImageInputCntr"]').append(jQuery('[id="multipleImageInput"]').html().replace(/{:count}/g, count + 1));

            thisObj.addClass('prevent-click');
            jQuery('[id="multiImageClose_' + count + '"]').removeClass('hide');
            jQuery('[id="multiImageImg_' + count + '"]').attr('src', e.target.result);
            jQuery('[id^="multiImageImg_"]').removeClass('errorBorder');
        };
        reader.readAsDataURL(input.files[0]);
    }
}

function removeImage(count) {
    jQuery('[id="multiImageCntr_' + count + '"]').remove();
}

function setModal(header_msg, body_msg) {
    jQuery('[id="modal_body_msg"]').html(body_msg);
    jQuery('[id="modal_header_msg"]').html(header_msg);
    jQuery('[id="errModal"]').modal('show');
}

function slideTo(id) {
    jQuery('html, body').animate({
        scrollTop: jQuery('[id="' + id + '"]').offset().top
    }, 800);
}

function modalTrigger(header, body_html) {
    jQuery('[id="modal_content"]').html(body_html);
    jQuery('[id="modal_header"]').html(header);

    jQuery('[id="popup_modal"]').modal('show');
}

function modalHide() {
    jQuery('[id="popup_modal"]').modal('hide');
}

function addModalLoader() {
    jQuery("[id='modal_content']").addClass('relative height_200');
    jQuery("[id='modal_content']").prepend("<div id='modal_loader_body' class='loader'></div>");
}

function remModalLoader() {
    jQuery("[id='modal_loader_body']").remove();
    jQuery("[id='modal_content']").removeClass('relative height_200');
}

function showFullScreenLoader() {
    var thisObj = jQuery('.overlay');
    thisObj.css("display", 'block');

    thisObj.addClass('relative');
    thisObj.prepend("<div id='fullScreenLoaderBody' class='loader'></div>");
}

function remFullScreenLoader() {
    var thisObj = jQuery('.overlay');
    thisObj.css("display", 'none');

    jQuery('[id="fullScreenLoaderBody"]').remove();
    thisObj.removeClass('relative');
}

function viewImageModal(title, img_src) {
    if (title == '' || title == undefined || title == 'undefined' || title == null || title == 'null' ||
            img_src == '' || img_src == undefined || img_src == 'undefined' || img_src == null || img_src == 'null') {
        return false;
    }
    body_html = '<div style="text-align:center">' +
            '<img src="' + img_src + '" onerror="this.src=\'' + base_url + 'assets/images/no_image.png\';" height="400px" width="auto">' +
            '</div>';
    modalTrigger(title, body_html);
}

function changeMechanic() {
    jQuery('[id="chooseMechForm"]').submit();
}

jQuery('[id="viewCustomer"]').on('click', function () {
    customer_id = jQuery(this).attr('customer_id');

    if (customer_id == '' || customer_id == undefined || customer_id == 'undefined' || customer_id == null || customer_id == 'null') {
        return true;
    }
    modalTrigger('Customer Details', '');
    addModalLoader();
    jQuery.ajax({
        url: base_url + "customer/getcustomerdata",
        type: 'POST',
        data: {'customer_id': customer_id},
        success: function (resp) {
            if (resp == '' || resp == undefined || resp == 'undefined' || resp == null || resp == 'null') {
                remModalLoader();
                jQuery('[id="modal_content"]').html('Something went wrong, please try again later...!');
                return false;
            }
            var resp_data = jQuery.parseJSON(resp);
            if (resp_data['status'] == '0') {
                remModalLoader();
                jQuery('[id="modal_content"]').html('Something went wrong, please try again later...!');
                return false;
            }
            var customer_data = resp_data['customer_data'], html = '', veh_html = '',
                    vehicle_data = resp_data['customer_data']['vehicle_data'];

            if (vehicle_data != '' && vehicle_data != undefined && vehicle_data != 'undefined' && vehicle_data != null && vehicle_data != 'null') {

                jQuery.each(vehicle_data, function (index, value) {
                    veh_html += '<span class="vechile-body disp-block marginBottom-5">' +
                            '<i class="fa fa-fw fa-car padRight-8p"></i>'
                            + value['car_name'] +
                            '</span>';
                });
                if (veh_html != '') {
                    veh_html = '<div class="col-xs-12"><div class="col-xs-2"></div><div class="col-xs-9">' +
                            '<label>Vehicles Added</label>' + veh_html + '</div></div>';
                }
            }

            html = '<div class="col-xs-12">' +
                    '<div class="col-md-2"> ' +
                    '<div class="form-group has-feedback"> ' +
                    '<img id="customerProfileImg" src="' + base_url + customer_data['image'] + '"' +
                    'height="100" width="100" /> ' +
                    '</div> ' +
                    '</div> ' +
                    '<div class="col-md-5"> ' +
                    '<div class="form-group has-feedback"> ' +
                    '<span style="padding-right: 43px;">Full Name </span> : ' +
                    '<label style="padding-left: 10px;">' +
                    customer_data['fullname'] +
                    '</label> ' +
                    '</div> ' +
                    '<div class="form-group has-feedback"> ' +
                    '<span style="padding-right: 68px;">Email </span> : ' +
                    '<label style="padding-left: 10px;">' +
                    customer_data['email'] +
                    '</label> ' +
                    '</div> ' +
                    '<div class="form-group has-feedback"> ' +
                    '<span style="padding-right: 13px;">Phone Number </span> : ' +
                    '<label style="padding-left: 10px;">' +
                    customer_data['phone_no'] +
                    '</label> ' +
                    '</div> ' +
                    '</div> ' +
                    '<div class="col-md-5"> ' +
                    '<div class="form-group has-feedback"> ' +
                    '<span style="padding-right: 56px;">District</span> : ' +
                    '<label style="padding-left: 10px;">' +
                    customer_data['district'] +
                    '</label> ' +
                    '</div> ' +
                    '</div> ' +
                    '</div>' + veh_html;

            remModalLoader();
            jQuery('[id="modal_content"]').html(html);

            jQuery('[id="customerProfileImg"]').error(function () {
                jQuery('[id="customerProfileImg"]').attr('src', base_url + 'assets/images/user_avatar.jpg');
            });

        },
        fail: function (xhr, textStatus, errorThrown) {
            remModalLoader();
            jQuery('[id="modal_content"]').html('Something went wrong, please try again later...!');
        },
        error: function (ajaxContext) {
            remModalLoader();
            jQuery('[id="modal_content"]').html('Something went wrong, please try again later...!');
        }
    });
});
jQuery('[id="createCustomerSubmit"]').on('click', function () {
    jQuery('[id="createCustomerForm"]').submit();
});

jQuery('[id="viewCity"]').on('click', function () {
    city_id = jQuery(this).attr('city_id');

    if (city_id == '' || city_id == undefined || city_id == 'undefined' || city_id == null || city_id == 'null') {
        return true;
    }
    modalTrigger('City Details', '');
    addModalLoader();
    jQuery.ajax({
        url: base_url + "city/getcitydata",
        type: 'POST',
        data: {'city_id': city_id},
        success: function (resp) {
            if (resp == '' || resp == undefined || resp == 'undefined' || resp == null || resp == 'null') {
                remModalLoader();
                jQuery('[id="modal_content"]').html('Something went wrong, please try again later...!');
                return false;
            }
            var resp_data = jQuery.parseJSON(resp);
            if (resp_data['status'] == '0') {
                remModalLoader();
                jQuery('[id="modal_content"]').html('Something went wrong, please try again later...!');
                return false;
            }
            var city_data = resp_data['city_data'], html = '', veh_html = '',
                    vehicle_data = resp_data['city_data']['vehicle_data'];

            if (vehicle_data != '' && vehicle_data != undefined && vehicle_data != 'undefined' && vehicle_data != null && vehicle_data != 'null') {

                jQuery.each(vehicle_data, function (index, value) {
                    veh_html += '<span class="vechile-body disp-block marginBottom-5">' +
                            '<i class="fa fa-fw fa-car padRight-8p"></i>'
                            + value['car_name'] +
                            '</span>';
                });
                if (veh_html != '') {
                    veh_html = '<div class="col-xs-12"><div class="col-xs-2"></div><div class="col-xs-9">' +
                            '<label>Vehicles Added</label>' + veh_html + '</div></div>';
                }
            }

            html = '<div class="col-md-5"> ' +
                    '<div class="form-group has-feedback"> ' +
                    '<span style="padding-right: 55px;">Zip Code </span> : ' +
                    '<label style="padding-left: 10px;">' +
                    city_data['zip_code'] +
                    '</label> ' +
                    '</div> ' +
                    '<div class="form-group has-feedback"> ' +
                    '<span style="padding-right: 55px;">Location </span> : ' +
                    '<label style="padding-left: 10px;">' +
                    city_data['location'] +
                    '</label> ' +
                    '</div> ' +
                    '</div> ' +
                    '</div>' + veh_html;

            remModalLoader();
            jQuery('[id="modal_content"]').html(html);

            jQuery('[id="customerProfileImg"]').error(function () {
                jQuery('[id="customerProfileImg"]').attr('src', base_url + 'assets/images/user_avatar.jpg');
            });

        },
        fail: function (xhr, textStatus, errorThrown) {
            remModalLoader();
            jQuery('[id="modal_content"]').html('Something went wrong, please try again later...!');
        },
        error: function (ajaxContext) {
            remModalLoader();
            jQuery('[id="modal_content"]').html('Something went wrong, please try again later...!');
        }
    });
});

jQuery('[id="viewProductDetails"]').on('click', function () {
    product_id = jQuery(this).attr('product_id');

    if (product_id == '' || product_id == undefined || product_id == 'undefined' || product_id == null || product_id == 'null') {
        return true;
    }
    modalTrigger('Product Details', '');
    addModalLoader();
    jQuery.ajax({
        url: base_url + "product/getproductdata",
        type: 'POST',
        data: {'product_id': product_id},
        success: function (resp) {
            if (resp == '' || resp == undefined || resp == 'undefined' || resp == null || resp == 'null') {
                remModalLoader();
                jQuery('[id="modal_content"]').html('Something went wrong, please try again later...!');
                return false;
            }
            var resp_data = jQuery.parseJSON(resp);
            if (resp_data['status'] == '0') {
                remModalLoader();
                jQuery('[id="modal_content"]').html('Something went wrong, please try again later...!');
                return false;
            }
            var product_data = resp_data['product_data'], html = '', veh_html = '',
                    vehicle_data = resp_data['product_data']['vehicle_data'];

            if (vehicle_data != '' && vehicle_data != undefined && vehicle_data != 'undefined' && vehicle_data != null && vehicle_data != 'null') {

                jQuery.each(vehicle_data, function (index, value) {
                    veh_html += '<span class="vechile-body disp-block marginBottom-5">' +
                            '<i class="fa fa-fw fa-car padRight-8p"></i>'
                            + value['car_name'] +
                            '</span>';
                });
                if (veh_html != '') {
                    veh_html = '<div class="col-xs-12"><div class="col-xs-2"></div><div class="col-xs-9">' +
                            '<label>Vehicles Added</label>' + veh_html + '</div></div>';
                }
            }

            html = '<div class="col-xs-12">' +
                    '<div class="col-md-2"> ' +
                    '<div class="form-group has-feedback"> ' +
                    '<img id="ProductImg" src="' + base_url + product_data['product_image'] + '"' +
                    'height="100" width="100" /> ' +
                    '</div> ' +
                    '</div> ' +
                    '<div class="col-md-5"> ' +
                    '<div class="form-group has-feedback"> ' +
                    '<span style="padding-right: 38px;">Product Name </span> : ' +
                    '<label style="padding-left: 10px;">' +
                    product_data['product_name'] +
                    '</label> ' +
                    '</div> ' +
                    '<div class="form-group has-feedback"> ' +
                    '<span style="padding-right: 43px;">Product Price </span> : ' +
                    '<label style="padding-left: 10px;">' +
                    product_data['product_price'] +
                    '</label> ' +
                    '</div> ' +
                    '<div class="form-group has-feedback"> ' +
                    '<span style="padding-right: 32px;">Category Name </span> : ' +
                    '<label style="padding-left: 10px;">' +
                    product_data['category_name'] +
                    '</label> ' +
                    '</div> ' +
                    '<div class="form-group has-feedback"> ' +
                    '<span style="padding-right: 53px;">Store Name </span> : ' +
                    '<label style="padding-left: 10px;">' +
                    ((product_data['store_name'] != null) ? product_data['store_name'] : "") +
                    '</label> ' +
                    '</div> ' +
                    '</div> ' +
                    '</div>' + veh_html;

            remModalLoader();
            jQuery('[id="modal_content"]').html(html);

            jQuery('[id="ProductImg"]').error(function () {
                jQuery('[id="ProductImg"]').attr('src', base_url + 'assets/images/user_avatar.jpg');
            });

        },
        fail: function (xhr, textStatus, errorThrown) {
            remModalLoader();
            jQuery('[id="modal_content"]').html('Something went wrong, please try again later...!');
        },
        error: function (ajaxContext) {
            remModalLoader();
            jQuery('[id="modal_content"]').html('Something went wrong, please try again later...!');
        }
    });
});

jQuery('[id="viewPromocode"]').on('click', function () {
    promo_id = jQuery(this).attr('promo_id');

    if (promo_id == '' || promo_id == undefined || promo_id == 'undefined' || promo_id == null || promo_id == 'null') {
        return true;
    }
    modalTrigger('Promocode Details', '');
    addModalLoader();
    jQuery.ajax({
        url: base_url + "promocode/getpromodata",
        type: 'POST',
        data: {'promo_id': promo_id},
        success: function (resp) {
            if (resp == '' || resp == undefined || resp == 'undefined' || resp == null || resp == 'null') {
                remModalLoader();
                jQuery('[id="modal_content"]').html('Something went wrong, please try again later...!');
                return false;
            }
            var resp_data = jQuery.parseJSON(resp);
            if (resp_data['status'] == '0') {
                remModalLoader();
                jQuery('[id="modal_content"]').html('Something went wrong, please try again later...!');
                return false;
            }
            var promo_data = resp_data['promo_data'], html = '', veh_html = '',
                    vehicle_data = resp_data['promo_data']['vehicle_data'];

            if (vehicle_data != '' && vehicle_data != undefined && vehicle_data != 'undefined' && vehicle_data != null && vehicle_data != 'null') {

                jQuery.each(vehicle_data, function (index, value) {
                    veh_html += '<span class="vechile-body disp-block marginBottom-5">' +
                            '<i class="fa fa-fw fa-car padRight-8p"></i>'
                            + value['car_name'] +
                            '</span>';
                });
                if (veh_html != '') {
                    veh_html = '<div class="col-xs-12"><div class="col-xs-2"></div><div class="col-xs-9">' +
                            '<label>Vehicles Added</label>' + veh_html + '</div></div>';
                }
            }

            html = '<div class="col-xs-12">' +
                    '<div class="col-md-2"> ' +
                    '<div class="form-group has-feedback"> ' +
                    '<img id="customerProfileImg" src="' + base_url + promo_data['image'] + '"' +
                    'height="100" width="100" /> ' +
                    '</div> ' +
                    '</div> ' +
                    '<div class="col-md-5"> ' +
                    '<div class="form-group has-feedback"> ' +
                    '<span style="padding-right: 38px;">Promocode Name </span> : ' +
                    '<label style="padding-left: 10px;">' +
                    promo_data['promo_code'] +
                    '</label> ' +
                    '</div> ' +
                    '<div class="form-group has-feedback"> ' +
                    '<span style="padding-right: 68px;">Starting Date </span> : ' +
                    '<label style="padding-left: 10px;">' +
                    promo_data['starting_date'] +
                    '</label> ' +
                    '</div> ' +
                    '<div class="form-group has-feedback"> ' +
                    '<span style="padding-right: 55px;">Ending Date </span> : ' +
                    '<label style="padding-left: 10px;">' +
                    promo_data['ending_date'] +
                    '</label> ' +
                    '</div> ' +
                    '</div> ' +
                    '</div> ' +
                    '</div>' + veh_html;

            remModalLoader();
            jQuery('[id="modal_content"]').html(html);

            jQuery('[id="customerProfileImg"]').error(function () {
                jQuery('[id="customerProfileImg"]').attr('src', base_url + 'assets/images/user_avatar.jpg');
            });

        },
        fail: function (xhr, textStatus, errorThrown) {
            remModalLoader();
            jQuery('[id="modal_content"]').html('Something went wrong, please try again later...!');
        },
        error: function (ajaxContext) {
            remModalLoader();
            jQuery('[id="modal_content"]').html('Something went wrong, please try again later...!');
        }
    });
});

jQuery('[id="viewCategory"]').on('click', function () {
    category_id = jQuery(this).attr('category_id');

    if (category_id == '' || category_id == undefined || category_id == 'undefined' || category_id == null || category_id == 'null') {
        return true;
    }
    modalTrigger('Category Details', '');
    addModalLoader();
    jQuery.ajax({
        url: base_url + "category/getcategorydata",
        type: 'POST',
        data: {'category_id': category_id},
        success: function (resp) {
            if (resp == '' || resp == undefined || resp == 'undefined' || resp == null || resp == 'null') {
                remModalLoader();
                jQuery('[id="modal_content"]').html('Something went wrong, please try again later...!');
                return false;
            }
            var resp_data = jQuery.parseJSON(resp);
            if (resp_data['status'] == '0') {
                remModalLoader();
                jQuery('[id="modal_content"]').html('Something went wrong, please try again later...!');
                return false;
            }
            var category_data = resp_data['category_data'], html = '', veh_html = '',
                    vehicle_data = resp_data['category_data']['vehicle_data'];

            if (vehicle_data != '' && vehicle_data != undefined && vehicle_data != 'undefined' && vehicle_data != null && vehicle_data != 'null') {

                jQuery.each(vehicle_data, function (index, value) {
                    veh_html += '<span class="vechile-body disp-block marginBottom-5">' +
                            '<i class="fa fa-fw fa-car padRight-8p"></i>'
                            + value['car_name'] +
                            '</span>';
                });
                if (veh_html != '') {
                    veh_html = '<div class="col-xs-12"><div class="col-xs-2"></div><div class="col-xs-9">' +
                            '<label>Vehicles Added</label>' + veh_html + '</div></div>';
                }
            }

            html = '<div class="col-xs-12">' +
                    '<div class="col-md-2"> ' +
                    '<div class="form-group has-feedback"> ' +
                    '<img id="categoryImg" src="' + base_url + category_data['category_image'] + '"' +
                    'height="100" width="100" /> ' +
                    '</div> ' +
                    '</div> ' +
                    '<div class="col-md-5"> ' +
                    '<div class="form-group has-feedback"> ' +
                    '<span style="padding-right: 38px;">Category Name </span> : ' +
                    '<label style="padding-left: 10px;">' +
                    category_data['category_name'] +
                    '</label> ' +
                    '</div> ' +
                    '<div class="form-group has-feedback"> ' +
                    '<span style="padding-right: 60px;">Store Name </span> : ' +
                    '<label style="padding-left: 10px;">' +
                    ((category_data['store_name'] != null) ? category_data['store_name'] : "") +
                    '</label> ' +
                    '</div> ' +
                    '<div class="form-group has-feedback"> ' +
                    '<span style="padding-right: 28px;">Store Description </span> : ' +
                    '<label style="padding-left: 10px;">' +
                    ((category_data['description'] != null) ? category_data['description'] : "") +
                    '</label> ' +
                    '</div> ' +
                    '</div> ' +
                    '</div>' + veh_html;

            remModalLoader();
            jQuery('[id="modal_content"]').html(html);

            jQuery('[id="categoryImg"]').error(function () {
                jQuery('[id="categoryImg"]').attr('src', base_url + 'assets/images/user_avatar.jpg');
            });

        },
        fail: function (xhr, textStatus, errorThrown) {
            remModalLoader();
            jQuery('[id="modal_content"]').html('Something went wrong, please try again later...!');
        },
        error: function (ajaxContext) {
            remModalLoader();
            jQuery('[id="modal_content"]').html('Something went wrong, please try again later...!');
        }
    });
});

jQuery('[id="viewSubCategory"]').on('click', function () {
    subcat_id = jQuery(this).attr('subcat_id');

    if (subcat_id == '' || subcat_id == undefined || subcat_id == 'undefined' || subcat_id == null || subcat_id == 'null') {
        return true;
    }
    modalTrigger('SubCategory Details', '');
    addModalLoader();
    jQuery.ajax({
        url: base_url + "sub_category/getsubcategorydata",
        type: 'POST',
        data: {'subcat_id': subcat_id},
        success: function (resp) {
            if (resp == '' || resp == undefined || resp == 'undefined' || resp == null || resp == 'null') {
                remModalLoader();
                jQuery('[id="modal_content"]').html('Something went wrong, please try again later...!');
                return false;
            }
            var resp_data = jQuery.parseJSON(resp);
            if (resp_data['status'] == '0') {
                remModalLoader();
                jQuery('[id="modal_content"]').html('Something went wrong, please try again later...!');
                return false;
            }
            var subcat_data = resp_data['subcat_data'], html = '', veh_html = '',
                    vehicle_data = resp_data['subcat_data']['vehicle_data'];

            if (vehicle_data != '' && vehicle_data != undefined && vehicle_data != 'undefined' && vehicle_data != null && vehicle_data != 'null') {

                jQuery.each(vehicle_data, function (index, value) {
                    veh_html += '<span class="vechile-body disp-block marginBottom-5">' +
                            '<i class="fa fa-fw fa-car padRight-8p"></i>'
                            + value['car_name'] +
                            '</span>';
                });
                if (veh_html != '') {
                    veh_html = '<div class="col-xs-12"><div class="col-xs-2"></div><div class="col-xs-9">' +
                            '<label>Vehicles Added</label>' + veh_html + '</div></div>';
                }
            }

            html = '<div class="col-xs-12">' +
                    '<div class="col-md-2"> ' +
                    '<div class="form-group has-feedback"> ' +
                    '<img id="subcategoryImg" src="' + base_url + subcat_data['image'] + '"' +
                    'height="100" width="100" /> ' +
                    '</div> ' +
                    '</div> ' +
                    '<div class="col-md-5"> ' +
                    '<div class="form-group has-feedback"> ' +
                    '<span style="padding-right: 38px;">Sub Category Name </span> : ' +
                    '<label style="padding-left: 10px;">' +
                    subcat_data['subcategory_name'] +
                    '</label> ' +
                    '</div> ' +
                    '<div class="form-group has-feedback"> ' +
                    '<span style="padding-right: 68px;">Category Name </span> : ' +
                    '<label style="padding-left: 10px;">' +
                    subcat_data['category_name'] +
                    '</label> ' +
                    '</div> ' +
                    '</div> ' +
                    '</div>' + veh_html;

            remModalLoader();
            jQuery('[id="modal_content"]').html(html);

            jQuery('[id="subcategoryImg"]').error(function () {
                jQuery('[id="subcategoryImg"]').attr('src', base_url + 'assets/images/user_avatar.jpg');
            });

        },
        fail: function (xhr, textStatus, errorThrown) {
            remModalLoader();
            jQuery('[id="modal_content"]').html('Something went wrong, please try again later...!');
        },
        error: function (ajaxContext) {
            remModalLoader();
            jQuery('[id="modal_content"]').html('Something went wrong, please try again later...!');
        }
    });
});

jQuery('[id="viewStoreProduct"]').on('click', function () {
    store_id = jQuery(this).attr('store_id');

    if (store_id == '' || store_id == undefined || store_id == 'undefined' || store_id == null || store_id == 'null') {
        return true;
    }
    modalTrigger('Store Product Details', '');
    addModalLoader();
    jQuery.ajax({
        url: base_url + "store/getstoredata",
        type: 'POST',
        data: {'store_id': store_id},
        success: function (resp) {
            if (resp == '' || resp == undefined || resp == 'undefined' || resp == null || resp == 'null') {
                remModalLoader();
                jQuery('[id="modal_content"]').html('Something went wrong, please try again later...!');
                return false;
            }
            var resp_data = jQuery.parseJSON(resp);
            if (resp_data['status'] == '0') {
                remModalLoader();
                jQuery('[id="modal_content"]').html('Something went wrong, please try again later...!');
                return false;
            }
            var store_data = resp_data['store_data'], html = '', veh_html = '',
                    vehicle_data = resp_data['store_data']['vehicle_data'];

            if (vehicle_data != '' && vehicle_data != undefined && vehicle_data != 'undefined' && vehicle_data != null && vehicle_data != 'null') {

                jQuery.each(vehicle_data, function (index, value) {
                    veh_html += '<span class="vechile-body disp-block marginBottom-5">' +
                            '<i class="fa fa-fw fa-car padRight-8p"></i>'
                            + value['car_name'] +
                            '</span>';
                });
                if (veh_html != '') {
                    veh_html = '<div class="col-xs-12"><div class="col-xs-2"></div><div class="col-xs-9">' +
                            '<label>Vehicles Added</label>' + veh_html + '</div></div>';
                }
            }

            html = '<div class="col-xs-12">' +
                    '<div class="col-md-2"> ' +
                    '<div class="form-group has-feedback"> ' +
                    '<img id="StoreImg" src="' + base_url + store_data['store_image'] + '"' +
                    'height="100" width="100" /> ' +
                    '</div> ' +
                    '</div> ' +
                    '<div class="col-md-5"> ' +
                    '<div class="form-group has-feedback"> ' +
                    '<span style="padding-right: 38px;">Store Name </span> : ' +
                    '<label style="padding-left: 10px;">' +
                    store_data['store_name'] +
                    '</label> ' +
                    '</div> ' +
                    '<div class="form-group has-feedback"> ' +
                    '<span style="padding-right: 24px;">Product Name </span> : ' +
                    '<label style="padding-left: 10px;">' +
                    store_data['product_name'] +
                    '</label> ' +
                    '</div> ' +
                    '</div> ' +
                    '</div>' + veh_html;

            remModalLoader();
            jQuery('[id="modal_content"]').html(html);

            jQuery('[id="storeImg"]').error(function () {
                jQuery('[id="storeImg"]').attr('src', base_url + 'assets/images/user_avatar.jpg');
            });

        },
        fail: function (xhr, textStatus, errorThrown) {
            remModalLoader();
            jQuery('[id="modal_content"]').html('Something went wrong, please try again later...!');
        },
        error: function (ajaxContext) {
            remModalLoader();
            jQuery('[id="modal_content"]').html('Something went wrong, please try again later...!');
        }
    });
});

jQuery('[id="viewShopperDetails"]').on('click', function () {
    var shopper_id = jQuery(this).attr('shopper_id');

    if (shopper_id == '' || shopper_id == undefined || shopper_id == 'undefined' || shopper_id == null || shopper_id == 'null') {
        return false;
    }
    modalTrigger('Shopper Details', '');
    addModalLoader();
    jQuery.ajax({
        url: base_url + "shopper/getshopperdata",
        type: 'POST',
        data: {'shopper_id': shopper_id},
        success: function (resp) {
            if (resp == '' || resp == undefined || resp == 'undefined' || resp == null || resp == 'null') {
                remModalLoader();
                jQuery('[id="modal_content"]').html('Something went wrong, please try again later...!');
                return false;
            }
            var resp_data = jQuery.parseJSON(resp);
            if (resp_data['status'] == '0') {
                remModalLoader();
                jQuery('[id="modal_content"]').html('Something went wrong, please try again later...!');
                return false;
            }
            var shopper_data = resp_data['shopper_data'], html = '', veh_html = '',
                    vehicle_data = resp_data['shopper_data']['vehicle_data'];

            if (vehicle_data != '' && vehicle_data != undefined && vehicle_data != 'undefined' && vehicle_data != null && vehicle_data != 'null') {

                jQuery.each(vehicle_data, function (index, value) {
                    veh_html += '<span class="vechile-body disp-block marginBottom-5">' +
                            '<i class="fa fa-fw fa-car padRight-8p"></i>'
                            + value['car_name'] +
                            '</span>';
                });
                if (veh_html != '') {
                    veh_html = '<div class="col-xs-12"><div class="col-xs-2"></div><div class="col-xs-9">' +
                            '<label>Vehicles Added</label>' + veh_html + '</div></div>';
                }
            }

            html = '<div class="col-xs-12">' +
                    '<div class="col-md-2"> ' +
                    '<div class="form-group has-feedback"> ' +
                    '<img id="StoreImg" src="' + base_url + shopper_data['shopper_image'] + '"' +
                    'height="100" width="100" /> ' +
                    '</div> ' +
                    '</div> ' +
                    '<div class="col-md-5"> ' +
                    '<div class="form-group has-feedback"> ' +
                    '<span style="padding-right: 38px;">Shopper Name </span> : ' +
                    '<label style="padding-left: 10px;">' +
                    shopper_data['shopper_name'] +
                    '</label> ' +
                    '</div> ' +
                    '<div class="form-group has-feedback"> ' +
                    '<span style="padding-right: 92px;">Email </span> : ' +
                    '<label style="padding-left: 10px;">' +
                    shopper_data['email'] +
                    '</label> ' +
                    '</div> ' +
                    '<div class="form-group has-feedback"> ' +
                    '<span style="padding-right: 37px;">Phone Number </span> : ' +
                    '<label style="padding-left: 10px;">' +
                    shopper_data['phone_no'] +
                    '</label> ' +
                    '</div> ' +
                    '</div> ' +
                    '<div class="col-md-5"> ' +
                    '<div class="form-group has-feedback"> ' +
                    '<span style="padding-right: 56px;">Store Name</span> : ' +
                    '<label style="padding-left: 10px;">' +
                    ((shopper_data['store_name'] != null) ? shopper_data['store_name'] : "") +
                    '</label> ' +
                    '</div> ' +
                    '</div> ' +
                    '</div>' + veh_html;

            remModalLoader();
            jQuery('[id="modal_content"]').html(html);

            jQuery('[id="StoreImg"]').error(function () {
                jQuery('[id="StoreImg"]').attr('src', base_url + 'assets/images/user_avatar.jpg');
            });

        },
        fail: function (xhr, textStatus, errorThrown) {
            remModalLoader();
            jQuery('[id="modal_content"]').html('Something went wrong, please try again later...!');
        },
        error: function (ajaxContext) {
            remModalLoader();
            jQuery('[id="modal_content"]').html('Something went wrong, please try again later...!');
        }
    });
});

jQuery('[id="viewStoreDetails"]').on('click', function () {
    store_id = jQuery(this).attr('store_id');

    if (store_id == '' || store_id == undefined || store_id == 'undefined' || store_id == null || store_id == 'null') {
        return true;
    }
    modalTrigger('Store Details', '');
    addModalLoader();
    jQuery.ajax({
        url: base_url + "stores/getstoresdata",
        type: 'POST',
        data: {'store_id': store_id},
        success: function (resp) {
            if (resp == '' || resp == undefined || resp == 'undefined' || resp == null || resp == 'null') {
                remModalLoader();
                jQuery('[id="modal_content"]').html('Something went wrong, please try again later...!');
                return false;
            }
            var resp_data = jQuery.parseJSON(resp);
            if (resp_data['status'] == '0') {
                remModalLoader();
                jQuery('[id="modal_content"]').html('Something went wrong, please try again later...!');
                return false;
            }
            var store_data = resp_data['store_data'], html = '', veh_html = '',
                    vehicle_data = resp_data['store_data']['vehicle_data'];

            if (vehicle_data != '' && vehicle_data != undefined && vehicle_data != 'undefined' && vehicle_data != null && vehicle_data != 'null') {

                jQuery.each(vehicle_data, function (index, value) {
                    veh_html += '<span class="vechile-body disp-block marginBottom-5">' +
                            '<i class="fa fa-fw fa-car padRight-8p"></i>'
                            + value['car_name'] +
                            '</span>';
                });
                if (veh_html != '') {
                    veh_html = '<div class="col-xs-12"><div class="col-xs-2"></div><div class="col-xs-9">' +
                            '<label>Vehicles Added</label>' + veh_html + '</div></div>';
                }
            }

            html = '<div class="col-xs-12">' +
                    '<div class="col-md-2"> ' +
                    '<div class="form-group has-feedback"> ' +
                    '<img id="StoreImg" src="' + base_url + store_data['store_image'] + '"' +
                    'height="100" width="100" /> ' +
                    '</div> ' +
                    '</div> ' +
                    '<div class="col-md-5"> ' +
                    '<div class="form-group has-feedback"> ' +
                    '<span style="padding-right: 38px;">Store Name </span> : ' +
                    '<label style="padding-left: 10px;">' +
                    store_data['store_name'] +
                    '</label> ' +
                    '</div> ' +
                    '<div class="form-group has-feedback"> ' +
                    '<span style="padding-right: 68px;">Store Description </span> : ' +
                    '<label style="padding-left: 10px;">' +
                    store_data['description'] +
                    '</label> ' +
                    '</div> ' +
                    '</div> ' +
                    '</div>' + veh_html;

            remModalLoader();
            jQuery('[id="modal_content"]').html(html);

            jQuery('[id="StoreImg"]').error(function () {
                jQuery('[id="StoreImg"]').attr('src', base_url + 'assets/images/user_avatar.jpg');
            });

        },
        fail: function (xhr, textStatus, errorThrown) {
            remModalLoader();
            jQuery('[id="modal_content"]').html('Something went wrong, please try again later...!');
        },
        error: function (ajaxContext) {
            remModalLoader();
            jQuery('[id="modal_content"]').html('Something went wrong, please try again later...!');
        }
    });
});

jQuery('[id="viewOrderDetails"]').on('click', function () {
    var order_id = jQuery(this).attr('order_id');

    if (order_id == '' || order_id == undefined || order_id == 'undefined' || order_id == null || order_id == 'null') {
        return true;
    }

    modalTrigger('Order Details', '');
    addModalLoader();
    jQuery.ajax({
        url: base_url + "orders/getorderdata",
        type: 'POST',
        data: {'order_id': order_id},
        success: function (resp) {
            if (resp == '' || resp == undefined || resp == 'undefined' || resp == null || resp == 'null') {
                remModalLoader();
                jQuery('[id="modal_content"]').html('Something went wrong, please try again later...!');
                return false;
            }
            var resp_data = jQuery.parseJSON(resp);
            if (resp_data['status'] == '0') {
                remModalLoader();
                jQuery('[id="modal_content"]').html('Something went wrong, please try again later...!');
                return false;
            }
            var html = '',
                    imgCount = 0,
                    optionalHtml = '';

            if (resp_data.product_image.length > 0) {
                optionalHtml = '<div class="col-md-12" style="padding-top:20px;">' +
                        '<div class="row"><label>Product Images</label></div>';

                optionalHtml += '<div class="row">' +
                        '<div class="col-md-12">';

                jQuery.each(resp_data.product_image, function (index, product_image) {
                    imgCount += 1;
                    optionalHtml += '<img id="optionalImage_' + imgCount + '" src="' + base_url + product_image.product_image + '" height="100" width="100" /> ';
                });
                optionalHtml += '</div>';
                optionalHtml += '</div>';
            }

            html = '<div class="col-xs-12">' +
                    '<div class="row"><label>Order Details</label></div>' +
                    '<div class="col-md-6">' +
                    '<div class="row">' +
                    '<div class="col-md-4">Order Id</div>' +
                    '<div class="col-md-1">:</div>' +
                    '<div class="col-md-6"><label>' + resp_data.order_data.order_id + '</label></div>' +
                    '</div> ' +
                    '<div class="row">' +
                    '<div class="col-md-4">User Name</div>' +
                    '<div class="col-md-1">:</div>' +
                    '<div class="col-md-6"><label>' + resp_data.order_data.fullname + '</label></div>' +
                    '</div> ' +
                    '<div class="row">' +
                    '<div class="col-md-4">Product Name</div>' +
                    '<div class="col-md-1">:</div>' +
                    '<div class="col-md-6"><label>' + resp_data.order_data.product_name + '</label></div>' +
                    '</div> ' +
                    '</div>' +
                    '<div class="col-md-6">' +
                    '<div class="row"> ' +
                    '<div class="col-md-4">Booking Date</div>' +
                    '<div class="col-md-1">:</div>' +
                    '<div class="col-md-6"><label>' + resp_data.order_data.booking_date + '</label></div>' +
                    '</div> ' +
                    '<div class="row"> ' +
                    '<div class="col-md-4">Schedule Date</div>' +
                    '<div class="col-md-1">:</div>' +
                    '<div class="col-md-6"><label>' + resp_data.order_data.scheduled_date + '</label></div> ' +
                    '</div> ' +
                    '<div class="row"> ' +
                    '<div class="col-md-4">Amount</div>' +
                    '<div class="col-md-1">:</div>' +
                    '<div class="col-md-6"><label>' + resp_data.order_data.total_amount + '</label></div> ' +
                    '</div> ';
            if (resp_data.order_data.status == '0' || resp_data.order_data.status == '2' || resp_data.order_data.status == '3') {
                html += '<div class="row"> ' +
                        '<div class="col-md-4">Expected Delivery Date</div>' +
                        '<div class="col-md-1">:</div>' +
                        '<div class="col-md-6"><label>' + resp_data.order_data.scheduled_date + '</label></div> ' +
                        '</div> ';
            } else if (resp_data.order_data.status == '1') {
                html += '<div class="row"> ' +
                        '<div class="col-md-4">Delivered On</div>' +
                        '<div class="col-md-1">:</div>' +
                        '<div class="col-md-6"><label>' + resp_data.order_data.scheduled_date + '</label></div> ' +
                        '</div> ';
            }
            html += '</div> ' +
                    optionalHtml +
                    '</div>';

            remModalLoader();
            jQuery('[id="modal_content"]').html(html);

            jQuery('[id^="optionalImage_"]').error(function () {
                jQuery('[id^="optionalImage_"]').attr('src', base_url + 'assets/images/no_image_text.png');
            });
        },
        fail: function (xhr, textStatus, errorThrown) {
            remModalLoader();
            jQuery('[id="modal_content"]').html('Something went wrong, please try again later...!');
        },
        error: function (ajaxContext) {
            remModalLoader();
            jQuery('[id="modal_content"]').html('Something went wrong, please try again later...!');
        }
    });
});

jQuery('[id="changeOrderStatus"]').on('click', function () {
    var order_id = jQuery(this).attr('order_id');
    var order_status = jQuery(this).attr('order_status');
    if (order_id == '' || order_id == undefined || order_id == 'undefined' || order_id == null || order_id == 'null') {
        return true;
    }
    modalTrigger('Change Order Detail Status', '');
    addModalLoader();

    var stat = '',
            dropOption = '<option selected disabled value="">--Change Status--</option>';

    switch (order_status) {
        case '2':
            stat = 'Order Places';
            dropOption += '<option value="3">Order Packed</option>';
            dropOption += '<option value="4">Order Shipped</option>';
            dropOption += '<option value="5">Order Delivered</option>';
            break;
        case '3':
            stat = 'Order Packed';
            dropOption += '<option value="4">Order Shipped</option>';
            dropOption += '<option value="5">Order Delivered</option>';
            break;
        case '4':
            stat = 'Ordered Shipped';
            dropOption += '<option value="5">Order Delivered</option>';
            break;
    }

    var html = '<form id="changeOrderStatus" role="form" method="post">' +
            '<div class="col-md-12" style="padding-top:10px">' +
            '<div class="col-md-3"><div class="row"><label>Current Status</label></div></div>' +
            '<div class="col-md-1"> : </div>' +
            '<div class="col-md-3"><div class="row"><label>' + stat + '</label></div></div>' +
            '</div>' +
            '<div class="col-md-12" style="padding-top:10px">' +
            '<div class="col-md-3"><div class="row"><label>Change Status</label></div></div>' +
            '<div class="col-md-1"> : </div>' +
            '<div class="col-md-3">' +
            '<div class="row">' +
            '<select id="orderStatus" onchange="statusChangeFun()" name="status" class="form-control required">' +
            dropOption +
            '</select>' +
            '</div>' +
            '</div>' +
            '<div class="col-md-5" id="deliverydatediv"></div>' +
            '</div>' +
            '<input type="hidden" name="order_id" id="order_id" value="' + order_id + '">' +
            '<div class="col-md-12"  style="padding-top:10px">' +
            '<div class="box-footer textCenterAlign">' +
            '<button type="button" onclick="changeOrderStatus(event);" class="btn btn-primary">Submit</button>' +
            '</div>' +
            '</div>' +
            '</form>';
    remModalLoader();
    jQuery('[id="modal_content"]').html(html);
});

function statusChangeFun() {
    var status = jQuery('[id="orderStatus"]').val();
    if (status == '3' || status == '4') {
        jQuery('[id="deliverydatediv"]').html('<div class="col-md-4">' +
                '<div class="row">' +
                '<label>Deliver On</label>' +
                '</div>' +
                '</div>' +
                '<div class="col-md-1"> : </div>' +
                '<div class="col-md-6">' +
                '<div class="row">' +
                '<input type="date" id="expected_delivery" class="form-control required" name="expected_delivery">' +
                '</div>' +
                '</div>');
    } else if (status == '5') {
        jQuery('[id="deliverydatediv"]').html('<div class="col-md-4">' +
                '<div class="row">' +
                '<label>Delivered on</label>' +
                '</div>' +
                '</div>' +
                '<div class="col-md-1"> : </div>' +
                '<div class="col-md-6">' +
                '<div class="row">' +
                '<input type="date" id="delivery" class="form-control required" name="expected_delivery">' +
                '</div>' +
                '</div>');
    }
}

function changeOrderStatus(e) {
    e.preventDefault();
    var errFlag = '1';

    jQuery('[id^="orderStatus"]').removeClass('errInput');

    var status = jQuery('[id="orderStatus"]').val();
    var order_id = jQuery('[id="order_id"]').val();

    if (status == '' || status == 'null' || status == 'NULL' || status == null) {
        jQuery('[id="orderStatus"]').addClass('errInput');
        return false;
    }

    if (status != '' || status != 'null' || status != 'NULL' || status != null) {
        errFlag = '0';
        if (status == '3' || status == '4') {
            var expected_delivery = jQuery('[id="expected_delivery"]').val();
            if (expected_delivery == '' || expected_delivery == 'null') {
                jQuery('[id="expected_delivery"]').addClass('errInput');
                errFlag = '1';
            }
        } else if (status == '5') {
            var scheduled_date = jQuery('[id="delivery"]').val();
            if (scheduled_date == '' || scheduled_date == 'null') {
                jQuery('[id="delivery"]').addClass('errInput');
                errFlag = '1';
            }
        }
    }
    if (errFlag == '1') {
        return false;
    }

    jQuery.ajax({
        url: base_url + "orders/changeorderstatus",
        type: 'POST',
        data: {'order_id': order_id, 'status': status, 'expected_date': expected_delivery},
        success: function (resp) {
            if (resp == '' || resp == undefined || resp == 'undefined' || resp == null || resp == 'null') {
                remModalLoader();
                jQuery('[id="modal_content"]').html('Something went wrong, please try again later...!');
                return false;
            }
            var resp_data = jQuery.parseJSON(resp);
            if (resp_data['status'] == '10') {
                remModalLoader();
                jQuery('[id="modal_content"]').html('Something went wrong, please try again later...!');
                return false;
            } else {
                remModalLoader();
                if (status == '0') {
                    var html = 'Ordered <br>(Deliver by ' + scheduled_date + ')';
                } else if (status == '2') {
                    var html = 'Order packed <br>(Deliver by ' + scheduled_date + ')';
                } else if (status == '3') {
                    var html = 'Order shipped <br>(Deliver by ' + scheduled_date + ')';
                } else if (status == '1') {
                    var html = 'Order delivered <br>(Delivered on ' + scheduled_date + ')';
                }
                jQuery('[id="orderStatus_' + order_id + '"]').html(html);
                jQuery('[id="modal_content"]').html('Status Changed Successfully.');

                setTimeout(function () {
                    modalHide();
                }, 1000);
                return false;
            }
        },
        fail: function (xhr, textStatus, errorThrown) {
            remModalLoader();
            jQuery('[id="modal_content"]').html('Something went wrong, please try again later...!');

            setTimeout(function () {
                modalHide();
            }, 1000);
        },
        error: function (ajaxContext) {
            remModalLoader();
            jQuery('[id="modal_content"]').html('Something went wrong,  try again later...!');

            setTimeout(function () {
                modalHide();
            }, 1000);
        }
    });
}
