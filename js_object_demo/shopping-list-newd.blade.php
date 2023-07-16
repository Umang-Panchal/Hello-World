@extends('layouts.shopping_list.index',['data' => $data,'modulestatus' => $modulestatus])
@section('metaTitle')
    Shopping List
@endsection
@section('pageCss')
    <link href="{{ asset('assets/css/icons/icomoon/styles.css') }}" rel="stylesheet" type="text/css">
    
    <script src="{{ asset('assets/cdn/js/jquery_3-6-0.js') }}"></script>
    <script src="{{ asset('assets/cdn/js/jquery_ui_1-13-1.js') }}"></script>
    <style type="text/css">
        .empty-sl {
            display: flex;
            justify-content: center;
            align-items: center;
            height: 200px;
        }
        .strike-through {
            text-decoration: line-through;
        }
        a.up, a.up:hover, .up i, a.down, .down i, a.down:hover {
            color: white;
        }

        .ui-front {
            z-index: 100 !important;
        }
    </style>
@endsection
@section('content')
    <div class="d-print-none calc-full-height">
        <div class="container-fluid">
            <div class="row">
                <div class="col-12 stick_to_top bg-white recipe_bottom_pills_wrapper">
                    <div class="py-3 d-flex justify-content-between align-items-center">

                        <div class="px-3 px-lg-0 pt-2 pt-md-0 unit_conversion_box order-2">
                            <div class="custom-radio d-inline-block pr-2 text-medium">
                                <input type="radio" id="imperial" name="conversion" value="imperial">
                                <label for="imperial" class="text-uppercase">Imperial</label>
                            </div>
                            <div class="custom-radio d-inline-block pr-2 text-medium">
                                <input type="radio" id="metric" name="conversion" value="metric">
                                <label for="metric" class="text-uppercase">Metric</label>
                            </div>
                        </div>
                        <div class="d-flex">

                            <button class="vote-for-content sl_vote-for-content  mr-2 add-item py-2" type="button"
                                    data-toggle="modal"
                                    data-target="#add_item" aria-expanded="true" data-title="Add Item"
                            >
                                Add Items
                            </button>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-12">
                    <div class="tab-content">
                        <div class="tab-pane active" id="shopping_list">
                            <div class="row">
                                <div class="col-12">
                                    <div class="shopping-table shopping_list_container py-3"> {{-- parent accordion starts --}}
                                        <div class="accordion" id="shoppingListAccordion">
                                            <div class="shopping_list_accordion closest-class" id="sortable">
                                                @forelse($data['place_list'] as $place)
                                                    {{--@if($place->is_static == \App\Utils\AppConstant::STATUS_INACTIVE)--}}
                                                    <div class="place hideHeaderShoppingList place_{{ $loop->iteration }} "
                                                         id="place_{{ $loop->iteration }}">
                                                        <div class="dairyAccordion bg-shadowGreen text-white d-flex justify-content-between align-items-center px-md-3 h-48 {{empty($place) ? 'invisible' : ''}}"
                                                             id="dairyAccordion" data-placeid="{{$place->id}}">
                                                            <div class="d-flex align-items-center">
                                                                <div class="p-md-0 p-1">
                                                                    <img src="{{asset('assets/images/icon/shoppinglist/whitedraggable.svg')}}"
                                                                         class="white-drag-icon more-actions-img cursor-pointer">
                                                                </div>
                                                                <h2 class="mb-0 text-uppercase list-heading ml-2">
                                                                    {{ $place->name }}
                                                                </h2>
                                                            </div>

                                                            @if($place->user_id)
                                                                <div class="d-flex">
                                                                    <button class="btn btn-link add-place"
                                                                            type="button"
                                                                            data-place_name="{{ $place->name }}"
                                                                            data-place_url="{{ route('shoppinglist-edit-place') }}"
                                                                            data-place_type="0"
                                                                            data-place_id="{{ $place->id }}"
                                                                            data-title="Edit Place"
                                                                            data-toggle="modal"
                                                                            data-target="#add_place"><img
                                                                                src="{{asset('assets/images/movement/yoga/edit_white.svg')}}"
                                                                                class="general_icons arrow_icons"
                                                                                data-toggle="tooltip"
                                                                                data-title="Edit">
                                                                    </button>
                                                                    <button class="btn btn-link" type="button"
                                                                            data-place_id="{{ $place->id }}"
                                                                            onClick="(function(){ $('#delete_place_id').val('{{ $place->id}}'); return false; })();return false;"
                                                                            data-toggle="modal"
                                                                            data-target="#delete_place_modal"><img
                                                                                src="{{asset('assets/images/icon/Delete_white.svg')}}"
                                                                                class="general_icons arrow_icons"
                                                                                data-toggle="tooltip"
                                                                                data-title="Delete">
                                                                    </button>
                                                                </div>
                                                            @else
                                                                <div class="collapse-icon-width">
                                                                    <button class="btn btn-link shopping-toggle d-block {{ ($place->shoppingListDetail && $place->shoppingListDetail->is_expand==0)?'collapsed':'' }}"
                                                                            type="button"
                                                                            data-toggle="collapse"
                                                                            data-target="#dairyCollapse{{ $place->id }}"
                                                                            aria-expanded="{{ ($place->shoppingListDetail && $place->shoppingListDetail->is_expand==0)?'false':'true' }}"
                                                                            aria-controls="dairyCollapse{{ $place->id }}"
                                                                            data-place_id="{{ $place->id }}"
                                                                            data-is_type="place"
                                                                            onClick="handleRotateIcon(this);">
                                                                        {{-- <img src="{{asset('assets/images/shopping_list/down_arrow_thin.svg')}}" class="general_icons arrow_icons">  --}}
                                                                    </button>


                                                                </div>
                                                            @endif
                                                        </div>
                                                        <div class="shopping-list-body">
                                                            <div id="dairyCollapse{{ $place->id }}"
                                                                 class="collapse overflow-x-hidden {{ ($place->shoppingListDetail && $place->shoppingListDetail->is_expand==0)?'':'show' }} sortable"
                                                                 aria-labelledby="produceHeading"
                                                                 data-parent="#shoppingListAccordion{{ $place->id }}"> {{-- child accordion begins --}}
                                                                @foreach($data['merged_data'] as $shopping_name)
                                                                    @if($shopping_name->shoppingName)
                                                                        <?php //dd($shopping_name);?>
                                                                        {{-- @if($shopping_name->shoppingName)--}}

                                                                        @if($place->id==$shopping_name->place_id)
                                                                            <div class="accordion shopping-name-list shoppping_items_id"
                                                                                 id="chedderAccordion_{{ $shopping_name->id}}"
                                                                                 data-id="{{ $shopping_name->id }}"
                                                                                 data-type="sl_shopping_list"
                                                                                 data-shoppingname="{{ $shopping_name->shoppingName->id }}"
                                                                                 data-placeid="{{$place->id}}"
                                                                                 data-sl_id="{{$shopping_name->user_sl_id}}"
                                                                            >
                                                                                <form method="post"
                                                                                      action="#"
                                                                                      name="manage-servings"
                                                                                      id="manage-servings">
                                                                                    @csrf


                                                                                    <div class="custom-filled-checkbox filter-option-basic w-100 ingredient_list_accordion aaa closest-class shoppinglist-items px-md-3 py-3 py-md-1">

                                                                                        <div class="d-flex align-items-start align-items-md-center justify-content-between w-100 shopping-list-bottom-border position-relative">
                                                                                            <div class="p-md-0 p-1 ">
                                                                                                <img src="{{asset('assets/images/icon/shoppinglist/Greydraggable.svg')}}"
                                                                                                     class="cursor-pointer more-actions-img grey-drag-icon mr-2">
                                                                                            </div>
                                                                                            <div class="d-md-flex justify-content-md-between w-100 sl-responsive-layout align-items-center sl-name">
                                                                                                <label class="sl-desktop-width-280"
                                                                                                       for="ingredient_checkbox{{ $shopping_name->id}}">
                                                                                                    <input id="ingredient_checkbox{{ $shopping_name->id}}"
                                                                                                           name="ingredient_checkbox{{ $shopping_name->id}}"
                                                                                                           type="checkbox"
                                                                                                           class="recipe_ingredients_item filled-checkbox ingredient_checkboxes check-ingredient"
                                                                                                           data-sl_id="{{ $shopping_name->shopping_name}}"
                                                                                                           data-status="{{ $shopping_name->is_checked}}"
                                                                                                           data-ing_id="{{ $shopping_name->id}}" {{ ($shopping_name->is_checked==1)?'checked':'' }}>
                                                                                                    <i class="helper"></i>
                                                                                                    <div class="d-inline-block ingredient-item-text recipeIngredientsServings sl-desktop-width-360"
                                                                                                         data-uuid="{{ $shopping_name->shoppingName->uuid }}">
                                                                                                        <span data-unit="metric"
                                                                                                              data-id="{{ $shopping_name->id }}"
                                                                                                              data-measure="{{ $shopping_name->ingredientInMeasure->measure->ingredientMeasureTag->constant }}"
                                                                                                              class="measure-value bold-text  {{ ($shopping_name->is_checked==1)?'strike-through':''}}"
                                                                                                              data-gmweight="{{($shopping_name->shoppingName->shoppingListExtraIng == null) ? $shopping_name->total_gram : $shopping_name->shoppingName->shoppingListExtraIng->extra_ing_gmweight}}"
                                                                                                              data-each_weight="{{ $shopping_name->shoppingName->weight }}"
                                                                                                              data-unit_format="{{ $shopping_name->shoppingName->format }}"
                                                                                                              data-type="{{ $shopping_name->shoppingName->unit_type }}"
                                                                                                              data-quantity="{{ $shopping_name->quantity }}"
                                                                                                              data-servings="{{ $shopping_name->userShoppingList->servings }}"
                                                                                                              data-name="{{ $shopping_name->shoppingName->shopping_name }}"
                                                                                                              data-constant="{{$shopping_name->ingredientInMeasure->measure->ingredientMeasureTag->constant}}"
                                                                                                              data-amount_value=""
                                                                                                              data-alter_name="{{ $shopping_name->shoppingName->alter_name }}"
                                                                                                              data-gram="{{$shopping_name->weight_gram}}"
                                                                                                              data-old_serving="{{ $shopping_name->userShoppingList->servings }}"
                                                                                                              data-default_gmweight="{{ $shopping_name->total_gram }}"
                                                                                                              data-default_refresh_gmweight="{{ $shopping_name->total_gram }}"
                                                                                                              data-default_initial_value=""
                                                                                                  >
                                                                                       {{ $shopping_name->shoppingName->shopping_name }}
                                                                                        </span>
                                                                                                    </div>
                                                                                                </label>
                                                                                                <div class="d-none d-md-flex">
                                                                                                    <div class="quantity">
                                                                                                        <div class="d-flex unit-select"
                                                                                                             data_extra_ing_id="{{ ($shopping_name->shoppingName->shoppingListExtraIng)?$shopping_name->shoppingName->shoppingListExtraIng->id:0 }}">
                                                                                                            <input type="number"
                                                                                                                   class="item-qty text-center extra_ingredient_input modal_textbox  extra_ing_amount extra_ingredient_input_number"
                                                                                                                   id="extra_ingredient_input{{ $shopping_name->id}}"
                                                                                                                   name="extra_ingredient_input"
                                                                                                                   value="{{ ($shopping_name->shoppingName->shoppingListExtraIng)?$shopping_name->shoppingName->shoppingListExtraIng->extra_ing_amount:0 }}"
                                                                                                                   data-default="{{ ($shopping_name->shoppingName->shoppingListExtraIng)?$shopping_name->shoppingName->shoppingListExtraIng->extra_ing_amount:0 }}"
                                                                                                                   step="{{ in_array($shopping_name->shoppingName->format, \App\Utils\AppConstant::FORMAT_IN_SL) ? 1 : 0.01}}"
                                                                                                                   min="{{ in_array($shopping_name->shoppingName->format, \App\Utils\AppConstant::FORMAT_IN_SL) ? 1 : 0.01}}"
                                                                                                                   pattern="^\d*(\.\d{0,2})?$"
                                                                                                                   style="{{($shopping_name->shoppingName->shoppingListExtraIng)? 'color:#DC3545;':'color:black;' }} cursor: pointer;"
                                                                                                                   data-new_serving="{{ $shopping_name->userShoppingList->servings }}"
                                                                                                                   data-old_serving="{{ $shopping_name->userShoppingList->servings }}"
                                                                                                                   data-new_original="{{ ($shopping_name->shoppingName->shoppingListExtraIng)?$shopping_name->shoppingName->shoppingListExtraIng->extra_ing_amount:0 }}"
                                                                                                                   data-temp_calc_quantity=""
                                                                                                                   data-default_extra_value=""
                                                                                                                   data-refresh_default_value=""
                                                                                                                   data-refresh_updated_value=""
                                                                                                            />
                                                                                                            <div style="border-bottom: 2px solid #4BBB8B; vertical-align: middle">
                                                                                                        <span class="text-center text-lowercase unit_format extra_ingredient_input modal_textbox"
                                                                                                              style="vertical-align: text-bottom"
                                                                                                              data-valueset="{{ ($shopping_name->shoppingName->shoppingListExtraIng)?$shopping_name->shoppingName->shoppingListExtraIng->extra_ing_unit:'g' }}"
                                                                                                              data-default="{{ ($shopping_name->shoppingName->shoppingListExtraIng)?$shopping_name->shoppingName->shoppingListExtraIng->extra_ing_unit:'g' }}"
                                                                                                              data-span_unit_format=""
                                                                                                        >
                                                                                                                     {{ ($shopping_name->shoppingName->shoppingListExtraIng)?$shopping_name->shoppingName->shoppingListExtraIng->extra_ing_unit:'g' }}
                                                                                                        </span>
                                                                                                            </div>
                                                                                                            @if($shopping_name->shoppingName->shoppingListExtraIng)
                                                                                                                <img src="{{asset('assets/images/shopping_list/Refresh.svg')}}"
                                                                                                                     class='refresh_extra_ing img-fluid refresh-btn cursor-pointer'
                                                                                                                     data-extra_ing='{{$shopping_name->shoppingName->shoppingListExtraIng->id}}'>

                                                                                                            @endif
                                                                                                        </div>
                                                                                                    </div>
                                                                                                </div>
                                                                                                <div class="d-flex">
                                                                                                    @mobile
                                                                                                    <div class="d-md-none d-flex mt-2">
                                                                                                        <div class="quantity">
                                                                                                            <div class="d-flex unit-select"
                                                                                                                 data_extra_ing_id="{{ ($shopping_name->shoppingName->shoppingListExtraIng)?$shopping_name->shoppingName->shoppingListExtraIng->id:0 }}">
                                                                                                                <input type="number"
                                                                                                                       class="item-qty text-center extra_ingredient_input modal_textbox  extra_ing_amount extra_ingredient_input_number"
                                                                                                                       id="extra_ingredient_input{{ $shopping_name->id}}"
                                                                                                                       name="extra_ingredient_input"
                                                                                                                       value="{{ ($shopping_name->shoppingName->shoppingListExtraIng)?$shopping_name->shoppingName->shoppingListExtraIng->extra_ing_amount:0 }}"
                                                                                                                       data-default="{{ ($shopping_name->shoppingName->shoppingListExtraIng)?$shopping_name->shoppingName->shoppingListExtraIng->extra_ing_amount:0 }}"
                                                                                                                       step="{{ in_array($shopping_name->shoppingName->format, \App\Utils\AppConstant::FORMAT_IN_SL) ? 1 : 0.01}}"
                                                                                                                       min="{{ in_array($shopping_name->shoppingName->format, \App\Utils\AppConstant::FORMAT_IN_SL) ? 1 : 0.01}}"
                                                                                                                       pattern="^\d*(\.\d{0,2})?$"
                                                                                                                       style="{{($shopping_name->shoppingName->shoppingListExtraIng)? 'color:#DC3545;':'color:black' }} cursor: pointer"
                                                                                                                       data-new_serving="{{ $shopping_name->userShoppingList->servings }}"
                                                                                                                       data-old_serving="{{ $shopping_name->userShoppingList->servings }}"
                                                                                                                       data-new_original="{{ ($shopping_name->shoppingName->shoppingListExtraIng)?$shopping_name->shoppingName->shoppingListExtraIng->extra_ing_amount:0 }}"
                                                                                                                       data-default_extra_value=""
                                                                                                                       data-refresh_default_value=""
                                                                                                                       data-refresh_updated_value=""
                                                                                                                />
                                                                                                                <div style="border-bottom: 2px solid #4BBB8B;vertical-align: middle">
                                                                                                        <span class="text-center text-lowercase unit_format extra_ingredient_input modal_textbox "
                                                                                                              style="vertical-align: text-bottom"
                                                                                                              data-valueset="{{ ($shopping_name->shoppingName->shoppingListExtraIng)?$shopping_name->shoppingName->shoppingListExtraIng->extra_ing_unit:'g' }}"
                                                                                                              data-default="{{ ($shopping_name->shoppingName->shoppingListExtraIng)?$shopping_name->shoppingName->shoppingListExtraIng->extra_ing_unit:'g' }}"
                                                                                                        >
                                                                                                                     {{ ($shopping_name->shoppingName->shoppingListExtraIng)?$shopping_name->shoppingName->shoppingListExtraIng->extra_ing_unit:'g' }}
                                                                                                        </span>
                                                                                                                </div>
                                                                                                                @if($shopping_name->shoppingName->shoppingListExtraIng)
                                                                                                                    <img src="{{asset('assets/images/shopping_list/Refresh.svg')}}"
                                                                                                                         class='refresh_extra_ing img-fluid refresh-btn cursor-pointer'
                                                                                                                         data-extra_ing='{{$shopping_name->shoppingName->shoppingListExtraIng->id}}'>
                                                                                                                @endif
                                                                                                            </div>
                                                                                                        </div>
                                                                                                    </div>
                                                                                                    @endmobile
                                                                                                    <div class="d-flex  align-items-center absolute-positin-sl-responsive mr-md-0 mr-3 sl-desktop-width-360 justify-content-end">
                                                                                                        <?php //dd($shopping_name->shoppingNotes)?>
                                                                                                        @if($shopping_name->shoppingNotes)
                                                                                                            <p class="app-color mb-0 d-lg-block d-none note-overflow cursor-pointer default-line-height shopping-notes_{{ $shopping_name->shoppingName->id }}"
                                                                                                               data-toggle="tooltip"
                                                                                                               data-placement="left"
                                                                                                               title="{{ $shopping_name->shoppingNotes->notes }}"
                                                                                                               data-text="{{ $shopping_name->shoppingNotes->notes }}"> {{ $shopping_name->shoppingNotes->notes }}</p>
                                                                                                            <button class="btn btn-link p-md-4 p-2 notes-btn  d-md-inline-block"
                                                                                                                    type="button"
                                                                                                                    data-toggle="modal"
                                                                                                                    data-target="#note-modal"
                                                                                                                    aria-expanded="true"
                                                                                                                    id="editNotes"
                                                                                                                    data-shoppingname="{{ $shopping_name->shoppingName->id }}">
                                                                                                                <img src="{{asset('assets/images/shopping_list/Note.svg')}}"
                                                                                                                     class="general_icons"
                                                                                                                     data-toggle="tooltip"
                                                                                                                     data-placement="left"
                                                                                                                     title="Edit Note">
                                                                                                            </button>

                                                                                                        @else

                                                                                                            <button class="btn btn-link p-md-4 p-2 notes-btn d-md-inline-block"
                                                                                                                    type="button"
                                                                                                                    data-toggle="modal"
                                                                                                                    data-target="#note-modal"
                                                                                                                    aria-expanded="true"
                                                                                                                    id="addNotes"
                                                                                                                    data-shoppingname="{{ $shopping_name->shoppingName->id }}">
                                                                                                                <img src="{{asset('assets/images/menu-plan/notes.png')}}"
                                                                                                                     class="general_icons"
                                                                                                                     data-toggle="tooltip"
                                                                                                                     data-placement="left"
                                                                                                                     title="Add Note"
                                                                                                                >
                                                                                                            </button>
                                                                                                        @endif
                                                                                                        <button data-shoppingname="{{$shopping_name->shoppingName->id}}"
                                                                                                                id="deleteShoppingList"
                                                                                                                class="btn btn-link notes-btn  d-md-inline-blockp-md-4 p-0  deleteNoted"
                                                                                                                type="button"
                                                                                                                data-toggle="modal"
                                                                                                                onclick="$('#delete_shopping_list_modal').modal('show');"
                                                                                                                aria-expanded="true"
                                                                                                        >

                                                                                                            <img src="{{asset('assets/images/shopping_list/Delete.svg')}}"
                                                                                                                 class="general_icons"
                                                                                                                 data-toggle="tooltip"
                                                                                                                 data-placement="left"
                                                                                                                 title="Delete Item">
                                                                                                        </button>
                                                                                                    </div>
                                                                                                </div>
                                                                                            </div>


                                                                                            <div class="d-flex align-items-center justify-content-end ">


                                                                                                <div class="collapse-icon-width">
                                                                                                    {{-- delete button--}}
                                                                                                    <button class="btn btn-link shopping-inner-collapse-toggle SL-Collapse {{ ($shopping_name->shoppingName->shoppingListDetail && $shopping_name->shoppingName->shoppingListDetail->is_expand==1)?'':'collapsed' }} accordanceArrow{{$loop->index}}"
                                                                                                            data-ajax=""
                                                                                                            data-place_id="{{ $shopping_name->place_id }}"
                                                                                                            data-sl_id="{{ $shopping_name->shopping_name}}"
                                                                                                            type="button"
                                                                                                            data-toggle="collapse"
                                                                                                            data-target="#chedderCollapse_{{ $shopping_name->id}}"
                                                                                                            data-s_name="{{ $shopping_name->shopping_name}}"
                                                                                                            data-is_type="shopinglist"
                                                                                                            aria-expanded="{{ ($shopping_name->shoppingName->shoppingListDetail && $shopping_name->shoppingName->shoppingListDetail->is_expand==1)?'true':'false' }}"
                                                                                                            aria-controls="chedderCollapse"
                                                                                                            onClick="handleRotateIcon(this);"
                                                                                                    >
                                                                                                        {{-- <img src="{{asset('assets/images/shopping_list/down_arrow_thick.svg')}}" class="general_icons arrow_icons">  --}}
                                                                                                    </button>

                                                                                                </div>

                                                                                            </div> {{-- child accordion ends --}}

                                                                                        </div>
                                                                                        @if($shopping_name->shoppingNotes)
                                                                                            <p class="text-muted font-italic mt-2 mb-0 d-lg-none
                                                                                     d-block note-overflow default-line-height shopping-notes_{{ $shopping_name->shoppingName->id }}"
                                                                                               data-toggle="tooltip"
                                                                                               data-placement="left"
                                                                                               title="{{ $shopping_name->shoppingNotes->notes }}"
                                                                                               data-text="{{ $shopping_name->shoppingNotes->notes }}"> {{ $shopping_name->shoppingNotes->notes }}</p>
                                                                                        @endif


                                                                                    </div>


                                                                                    <div id="chedderCollapse_{{ $shopping_name->id}}"
                                                                                         class="collapse {{ ($shopping_name->shoppingName->shoppingListDetail && $shopping_name->shoppingName->shoppingListDetail->is_expand==1)?'show':'' }} cheddar_clp"
                                                                                         aria-labelledby="Chedder Cheese"
                                                                                         data-parent="#chedderAccordion_{{ $shopping_name->id}}">

                                                                                        <div class="ingredient-item-body"> {{-- sub child accordion begins --}}
                                                                                            <div class="accordion"
                                                                                                 id="chedderCheeseRecipeAccordion">
                                                                                                <div class="d-flex justify-content-between align-items-center closest-class">
                                                                                                    <p class="app-color text-uppercase bold-text sl-recipe-title{{$shopping_name->id}}">
                                                                                                        Recipes </p>
                                                                                                </div>
                                                                                            </div>
                                                                                            <div id="chedderCheeseRecipeCollapse_{{ $shopping_name->id }}"
                                                                                                 class="recipe_accordion collapse_accordion pb-2 pl-4 {{ ($shopping_name->shoppingName->shoppingListDetail && $shopping_name->shoppingName->shoppingListDetail->is_recipe_expand==1)?'show':'' }}"
                                                                                                 aria-labelledby="chedderCheeseRecipeHeading"
                                                                                                 data-parent="#chedderCheeseRecipeAccordion"
                                                                                                 data-type="recipe"
                                                                                                 data-new_quantity="{{$shopping_name->quantity}}"
                                                                                            >
                                                                                                <a href="#"
                                                                                                   class="shopping_list_recipe_name">
                                                                                                    Recipe Name
                                                                                                </a>
                                                                                                <div class="d-flex align-items-center recipe-names">
                                                                                                    <a href="#"
                                                                                                       class="shopping_list_recipe_name">
                                                                                                        Recipe Name
                                                                                                    </a>
                                                                                                    <div class="servings_wrapper">
                                                                                                        <div class="text-uppercase">
                                                                                                            <div class="d-flex servings_box default_serving_box align-items-center mx-2">
                                                                                                                <div class="servings align-self-center">
                                                                                                                    servings
                                                                                                                </div>
                                                                                                                <div>
                                                                                                                    <img src="{{asset('assets/images/icon/Minus-grey.svg')}}"
                                                                                                                         class="recipe-modal-btn sub-serving">
                                                                                                                </div>
                                                                                                                <div class="quantity">
                                                                                                                    <input type="text"
                                                                                                                           class="no-borders input-sm servings-btn"
                                                                                                                           id="recipenamechangeservings"
                                                                                                                           name="recipenamechangeservings"
                                                                                                                           value="1"
                                                                                                                           tabindex="0"
                                                                                                                           minlength="1"
                                                                                                                           maxlength="3">
                                                                                                                </div>
                                                                                                                <div>
                                                                                                                    <img src="{{asset('assets/images/icon/Add-grey.svg')}}"
                                                                                                                         class="recipe-modal-btn add-serving">
                                                                                                                </div>
                                                                                                            </div>
                                                                                                        </div>
                                                                                                    </div>
                                                                                                    <img src="{{asset('assets/images/icon/delete_blue.svg')}}"
                                                                                                         class="general_icons cursor-pointer recipe-delete-icon"
                                                                                                         data-toggle="modal"
                                                                                                         data-target="#delete_modal">
                                                                                                </div>
                                                                                            </div>
                                                                                            <input type="hidden"
                                                                                                   id="shoppingname"
                                                                                                   name="shoppingname"
                                                                                                   value="{{ $shopping_name->shopping_name}}">
                                                                                            <div class="accordion"
                                                                                                 id="chedderCheeseIngredientAccordion_{{ $shopping_name->id}}">
                                                                                                <div class="d-flex justify-content-between align-items-center closest-class ">
                                                                                                    <p class="app-color text-uppercase bold-text sl-extra-ing-title{{$shopping_name->id}}">
                                                                                                        Extra
                                                                                                        Ingredient </p>
                                                                                                </div>
                                                                                            </div>
                                                                                            <div id="chedderCheeseIngredientCollapse_{{ $shopping_name->id}}"
                                                                                                 class="ingredient_accordion collapse_accordion {{ ($shopping_name->shoppingName->shoppingListDetail && $shopping_name->shoppingName->shoppingListDetail->is_ing_expand==1)?'show':'' }} pb-2 pl-4"
                                                                                                 aria-labelledby="chedderCheeseIngredientHeading"
                                                                                                 data-type="ingredient"
                                                                                                 data-parent="#chedderCheeseIngredientAccordion_{{ $shopping_name->id}}"
                                                                                                 data-new_quantity="{{$shopping_name->quantity}}"
                                                                                            >
                                                                                            </div>
                                                                                            <div class="d-flex justify-content-center justify-content-md-end my-2 mb-3">
                                                                                            </div>{{-- sub child accordion ends --}}
                                                                                        </div>
                                                                                    </div> {{-- child accordion ends --}}
                                                                                </form>
                                                                            </div>
                                                                        @endif
                                                                        {{--@endif--}}

                                                                    @else
                                                                        @if($shopping_name->place_id==$place->id)
                                                                            @component('components.shopping_list.add_item_component',['shopping_name'=>$shopping_name,'place'=>$place])
                                                                            @endcomponent
                                                                        @endif
                                                                    @endif
                                                                @endforeach

                                                            </div> {{-- parent accordion ends --}} </div>
                                                    </div>
                                                    {{--@endif--}}

                                                @empty

                                                @endforelse

                                            </div> {{-- add place group --}}
                                        </div>
                                    </div>
                                </div>
                                <div class="col-12">
                                    @component('components.shopping_list.show_recipe_list_component',['recipe_list'=>$data['recipe_list']])
                                    @endcomponent
                                </div>
                            </div>
                        </div>
                        {{-- recipe list --}}
                        {{--tab pane hide div--}}
                    </div>
                </div>
            </div>
        </div>
        {{--</form> form end--}}
        <div id="clear_shopping_list_new" class="modal modal-basic fade delete_collection_name modal-lg-basic"
             tabindex="-1" role="dialog" aria-labelledby="Delete Modal" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered ">
                <div class="modal-content no-padding">
                    <div class="modal-header">
                        <h5 class="modal-title text-uppercase modal-title-basic semi-bold" id="deleteCollectionName">
                            Clear Items</h5>
                        <button type="button" class="close close-modal" data-dismiss="modal" aria-label="Close"><img
                                    src="{{asset('assets/images/icon/default_modal_close.svg')}}"
                                    class="close-modal-img"></button>
                    </div>
                    <div class="modal-body print-modal no-padding">
                        <div class="container-fluid">
                            <div class="row gradient-strip">
                            </div>
                            <form action="{{ route('shoppinglist.clearShoppingList') }}" method="post"
                                  id="clearOptions">
                                @csrf
                                <input type="hidden" id="items_id" name="items_id" value="">
                                <input type="hidden" id="sl_id" name="sl_id" value="">
                                <div class="row">
                                    <div class="col-12">
                                        <div class="d-flex flex-column">
                                            <div id="printOptionDisplay">
                                                <div class="custom-filled-checkbox filter-option-basic w-100 text-capitalize playlist-checkboxes">
                                                    <label class="playlist-names" for="clear_selected_items">
                                                        <input
                                                                name="options" type="radio" class="filled-checkbox"
                                                                value="1" id="clear_selected_items">
                                                        <i class="helper modal-checkboxes"></i>Clear Selected Items
                                                    </label>
                                                </div>
                                                <div class="custom-filled-checkbox filter-option-basic w-100 text-capitalize playlist-checkboxes">
                                                    <label class="playlist-names" for="clear_entire_sl">
                                                        <input
                                                                name="options" type="radio" class="filled-checkbox"
                                                                value="2" id="clear_entire_sl" checked>
                                                        <i
                                                                class="helper modal-checkboxes"></i>
                                                        Clear Entire Shopping List
                                                    </label>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="d-flex add-to-toggle-footer justify-content-center">
                                            <div>
                                                <button type="submit" class="text-uppercase mx-2 done_action_btn"> OK
                                                </button>
                                            </div>
                                            <div>
                                                <button class="text-uppercase mx-2 cancel_action_btn"
                                                        data-dismiss="modal"
                                                        aria-label="Close"> Cancel
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        {{-- delete modal --}}
        @component('components.shopping_list.delete_modal')
        @endcomponent
        {{--print--}}
        <div id="print_sl_modal" class="modal modal-basic fade delete_collection_name modal-lg-basic print-options"
             tabindex="-1" role="dialog" aria-labelledby="Print SL Modal" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered ">
                <div class="modal-content no-padding">
                    <div class="modal-header">
                        <h5 class="modal-title text-uppercase modal-title-basic semi-bold" id="deleteCollectionName">
                            Print Shopping List</h5>
                        <button type="button" class="close close-modal" data-dismiss="modal" aria-label="Close">
                            <img
                                    src="{{asset('assets/images/icon/default_modal_close.svg')}}"
                                    class="close-modal-img"></button>
                    </div>
                    <div class="modal-body print-modal no-padding">
                        <div class="container-fluid">
                            <div class="row gradient-strip">
                            </div>
                            <form action="{{route('shoppinglist.print')}}" method="post"
                                  id="printOptions">
                                @csrf
                                <div class="row">
                                    <div class="col-12">
                                        <div class="d-flex flex-column">
                                            <div id="printOptionDisplay">
                                                <div class="custom-filled-checkbox filter-option-basic w-100 text-capitalize playlist-checkboxes">
                                                    <label class="playlist-names" for="all">
                                                        <input
                                                                name="options[]" type="checkbox"
                                                                class="filled-checkbox print_checkbox poptions"
                                                                value="all" id="all">
                                                        <i class="helper modal-checkboxes"></i>All
                                                    </label>
                                                </div>
                                                <div class="custom-filled-checkbox filter-option-basic w-100 text-capitalize playlist-checkboxes">
                                                    <label class="playlist-names" for="ingredients">
                                                        <input
                                                                name="options[]" type="checkbox"
                                                                class="filled-checkbox print_checkbox poptions"
                                                                value="ingredients" id="ingredients">
                                                        <i
                                                                class="helper modal-checkboxes"></i>
                                                        Ingredients
                                                    </label>
                                                </div>
                                                <div class="custom-filled-checkbox filter-option-basic w-100 text-capitalize playlist-checkboxes">
                                                    <label class="playlist-names" for="added_items">
                                                        <input
                                                                name="options[]" type="checkbox"
                                                                class="filled-checkbox print_checkbox poptions"
                                                                value="added_items" id="added_items">
                                                        <i
                                                                class="helper modal-checkboxes"></i>
                                                        Added Items
                                                    </label>
                                                </div>
                                                <div class="custom-filled-checkbox filter-option-basic w-100 text-capitalize playlist-checkboxes">
                                                    <label class="playlist-names" for="recipes_list">
                                                        <input
                                                                name="options[]" type="checkbox"
                                                                class="filled-checkbox print_checkbox poptions"
                                                                value="recipes_list" id="recipes_list">
                                                        <i
                                                                class="helper modal-checkboxes"></i>
                                                        Recipes List
                                                    </label>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="d-flex add-to-toggle-footer justify-content-center">
                                            <div>
                                                <button type="submit" class="text-uppercase mx-2 done_action_btn"> Print
                                                </button>
                                            </div>
                                            <div>
                                                <button class="text-uppercase mx-2 cancel_action_btn"
                                                        data-dismiss="modal"
                                                        aria-label="Close"> Cancel
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{--for delete item/shopping name--}}
        <div id="delete_shopping_list_modal" class="modal modal-basic fade delete_collection_name modal-lg-basic"
             tabindex="-1"
             role="dialog" aria-labelledby="Delete Modal" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered ">
                <div class="modal-content no-padding">
                    <div class="modal-header">
                        <h5 class="modal-title text-uppercase modal-title-basic semi-bold" id="deleteCollectionName">
                            Delete Item</h5>
                        <button type="button" class="close close-modal" data-dismiss="modal" aria-label="Close"><img
                                    src="{{asset('assets/images/icon/default_modal_close.svg')}}"
                                    class="close-modal-img"></button>
                    </div>
                    <form action="{{ route('deleteshoppinglist') }}" method="post">
                        @csrf
                        <input type="hidden" name="delete_shoppingName" id="delete_shoppingName">
                        <div class="modal-body no-padding">
                            <div class="container-fluid">
                                <div class="row gradient-strip"></div>
                                <div class="row">
                                    <div class="col text-center">
                                        <div class="app-color my-4 text-semibold" id="deleteItem"> Would you like to
                                            delete this item?
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-12">
                                        <div class="text-center py-3">
                                            <button type="submit" class="text-uppercase mx-2 done_action_btn"> OK
                                            </button>
                                            <button class="text-uppercase mx-2 cancel_action_btn" data-dismiss="modal"
                                                    aria-label="Close"> Cancel
                                            </button>

                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        {{--notes--}}

        {{-- Delete Note Component  --}}
        <div id="delete_note_modal_new" class="modal modal-basic fade" tabindex="-1" role="dialog"
             aria-labelledby="add to collection" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content no-padding">
                    <div class="modal-header">
                        <h5 class="modal-title text-uppercase modal-title-basic semi-bold" id="addToCollection">Delete
                            Note</h5>
                        <button type="button" class="close close-modal" data-dismiss="modal" aria-label="Close"><img
                                    src="{{asset('assets/images/icon/default_modal_close.svg')}}"
                                    class="close-modal-img"></button>
                    </div>
                    <div class="modal-body no-padding">
                        <div class="container-fluid">
                            <div class="row gradient-strip"></div>
                            <div class="container-fluid">
                                <div class="row">
                                    <div class="col-12 py-3">
                                        <form method="post" action="{{route('shoppinglist-delete-note')}}"
                                        >
                                            @csrf
                                            <input type="hidden" name="shoppingname" id="shoppingname_delete" value="">
                                            <div class="col-12 form-group form-group-material">
                                                <div class="row">
                                                    <div class="col text-center">
                                                        <div class="app-color my-4 text-semibold" id="deleteItem"> Would
                                                            you like to
                                                            delete this note?
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-12">
                                                    <div class="text-center py-3">
                                                        <button type="submit"
                                                                class="text-uppercase mx-2 done_action_btn"> OK
                                                        </button>
                                                        <button class="text-uppercase mx-2 cancel_action_btn"
                                                                data-dismiss="modal"
                                                                aria-label="Close"> Cancel
                                                        </button>

                                                    </div>
                                                </div>
                                            </div>


                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- deleteItems new Component --}}
        <div id="delete_item_modal" class="modal modal-basic fade delete_item_modal modal-lg-basic" tabindex="-1"
             role="dialog" aria-labelledby="Delete Modal" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered ">
                <div class="modal-content no-padding">
                    <div class="modal-header">
                        <h5 class="modal-title text-uppercase modal-title-basic semi-bold" id="deleteCollectionName">
                            Delete Item</h5>
                        <button type="button" class="close close-modal" data-dismiss="modal" aria-label="Close"><img
                                    src="{{asset('assets/images/icon/default_modal_close.svg')}}"
                                    class="close-modal-img"></button>
                    </div>
                    <form action="{{ route('shoppinglist-delete-item') }}" method="post">
                        @csrf
                        <input type="hidden" name="item_id" id="delete_item_id" value="">
                        <div class="modal-body no-padding">
                            <div class="container-fluid">
                                <div class="row gradient-strip"></div>
                                <div class="row">
                                    <div class="col text-center">
                                        <div class="app-color my-4 text-semibold" id="deleteItem"> Would you like to
                                            delete this item?
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-12">
                                        <div class="text-center py-3">
                                            <button type="submit" class="text-uppercase mx-2 done_action_btn"> OK
                                            </button>
                                            <button class="text-uppercase mx-2 cancel_action_btn" data-dismiss="modal"
                                                    aria-label="Close"> Cancel
                                            </button>

                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        @component('components.shopping_list.shopping_list_note_modal_component')
        @endcomponent

        @component('components.shopping_list.add_item_modal',['place_in_store'=>$data['place_list_new']])
        @endcomponent

        @component('components.shopping_list.delete_note_modal')
        @endcomponent

        @component('components.shopping_list.share_shopping_list_modal')
        @endcomponent
        @endsection
        @section('pageJs')
            <script type="text/javascript"
                    src="{{ asset('assets/js/plugins/validation/jquery-validation.js') }}"></script>
            <script type="text/javascript" src="{{ asset('assets/js/plugins/validation/validate.min.js') }}"></script>
            <!-- LazyLoad JavaScript -->
            <script type="text/javascript" src="https://cdn.jsdelivr.net/npm/vanilla-lazyload@12.0.0/dist/lazyload.min.js"></script>
            <!-- Latest compiled and minified JavaScript -->
            <script type="text/javascript" src="{{ asset('assets/cdn/js/bootstrap_select_1-13-1.min.js') }}"></script>
            <script type="text/javascript" src="{{ asset('js/conversion.js') }}"></script>
            <script type="text/javascript" src="{{ asset('js/sl_calculation.js') }}"></script>

            <script type="text/javascript">
                (function () {
                    new LazyLoad({
                        elements_selector: ".lazy",
                    });
                })();
            </script>
            <script>
                let user_id = '{{ $data['user_id'] }}';
                let calc_obj_temp = '';
                //for add item modal save button's other-item class

                $("#clearOptions form").validate({
                    rules: {
                        options: {
                            required: true,
                        },
                    },
                });
                $(function () {
                    $('input[type=radio][name=options]').change(function () {
                        if (this.value == '1') {

                            var sl_id = [];
                            $('.recipe_ingredients_item:checkbox:checked').each(function () {
                                sl_id.push($(this).attr("data-sl_id"));
                            });

                            var items_id = [];
                            $('.cip:checkbox:checked').each(function () {
                                items_id.push($(this).attr("data-id"));
                            });
                            $('#sl_id').val(sl_id.toString());
                            $('#items_id').val(items_id.toString());
                        }
                    });

                    $('.up').on('click', function (e) {
                        var id = $(this).attr('data-priority');
                        var tests = $('#' + id);
                        if (tests.prev().hasClass('place')) {
                            tests.first().insertBefore(tests.prev());
                            var values = [];
                            $('.dairyAccordion').each(function () {
                                var value = $(this).attr('data-placeid');
                                if (value != undefined) {
                                    values.push(value);
                                }
                            });
                            updateOrder(values);
                        }
                    });
                    $('.down').on('click', function (e) {
                        var id = $(this).attr('data-priority');
                        var tests = $('#' + id);
                        if (tests.next().hasClass('place')) {
                            tests.first().insertAfter(tests.next());
                            var values = [];
                            $('.dairyAccordion').each(function () {
                                var value = $(this).attr('data-placeid');
                                if (value != undefined) {
                                    values.push(value);
                                }
                            });
                            updateOrder(values);
                            $(".down").last().addClass('show');
                        }
                    });
                });
                $(function () {

                    $("#notes-frm").validate({
                        rules: {
                            notes: {
                                required: true,
                                maxlength: 191
                            }
                        },
                        messages: {
                            notes: {
                                required: "Not can't empty.",
                                maxlength: "The notes may not be greater than 191 characters."
                            }
                        }
                    });
                });
                $(document).scroll(function () {
                    //scrollable fixed
                    if ($(this).scrollTop() > 135) {
                        $('.stick_to_top').addClass('fixed_menu');
                    } else {
                        $('.stick_to_top').removeClass('fixed_menu');
                    }

                });


                $(document).ready(function () {

                    $(document).on('click', '#addNotes', function () {
                        $('textarea#notes').val('');
                        $('#shoppingnames').val($(this).attr('data-shoppingname'));
                        $('#todo_ahead_heading').text('Add Note');
                        $('.iconsize').css('display', 'none');
                        $('#deleteNotes').data('shoppingname', $(this).attr('data-shoppingname'));
                    });
                    $(document).on('click', '#editNotes', function () {
                        var id = $(this).attr('data-shoppingname');

                        // console.log("shopping notes ",id);
                        $('textarea#notes').text($('.shopping-notes_' + id).attr('data-text'));
                        $('#shoppingnames').val($(this).attr('data-shoppingname'));
                        $('#shoppingnames').data('shoppingname', $(this).attr('data-shoppingname'));
                        $('#deleteNotes').data('shoppingname', $(this).attr('data-shoppingname'));
                        $('#shoppingname_delete').val(id);
                        $('#todo_ahead_heading').text('Edit Note');
                    });
                    $(document).on('click', '#deleteNotes', function () {
                        var id = $(this).attr('data-shoppingname');
                        //$('#shoppingname_delete').val(id);
                        $('#deleteNotes').data('shoppingname', $(this).attr('data-shoppingname'));
                        $('#shoppingname').data('shoppingname', $(this).attr('data-shoppingname'));

                    });
                    $(document).on('click', '.deleteNoted', function () {
                        var id = $(this).attr('data-shoppingname');
                        $('#deleteNotes').data('shoppingname', $(this).attr('data-shoppingname'));
                        $('#shoppingname').data('shoppingname', $(this).attr('data-shoppingname'));
                        $('#shoppingname_delete').val(id);
                    });
                    // readonly support for add ingredient
                    $(document).on('change', "#measure_id", function () {
                        $('#quantity').removeAttr('readonly');
                        //that.find('#ingredientinmeasureid').val($(this).find(":selected").attr('id'));
                        $('.ing-calaries').attr('data-cal', $(this).find(":selected").attr('data-cal'));
                        //Calaries per servings
                        $('.ing-calaries').closest('.form-group').addClass('d-none');

                        let amount = $('#quantity').val();
                        if (amount != '' && amount != '0') {
                            let cal = parseFloat($(this).find(":selected").attr('data-cal'));
                            cal = cal * parseFloat(amount);
                            $('.ing-calaries').text(Math.round(cal) + ' Kcal');
                            //Hide calaries when ingredient select
                            $('.ing-calaries').closest('.form-group').removeClass('d-none');
                        }
                    });
                    $(document).on('change keyup', "#quantity", function () {
                        $('#servings').removeAttr('readonly');
                        let cal = parseFloat($('.ing-calaries').attr('data-cal'));
                        let amount = $(this).val();
                        if (amount != '' && amount != '0') {
                            cal = cal * parseFloat(amount);
                            cal = Math.round(cal);
                            $('.ing-calaries').text(cal + ' Kcal');
                            //showing calaries once amount filled
                            $('.ing-calaries').closest('.form-group').removeClass('d-none');
                        } else {
                            $('.ing-calaries').closest('.form-group').addClass('d-none');
                        }

                    });
                    if (!sessionStorage.getItem('Sltab') || sessionStorage.getItem('Sltab') == 'shopping_list') {
                        sessionStorage.setItem('Sltab', 'shopping_list');
                    } else {
                        $('.sl-list:first,.sl-list:first a,.tab-pane:first').removeClass('active');
                        $('.sl-list:last,.sl-list:last a,.tab-pane:last').addClass('active');
                        //$('#'+$('.sl-list:last').attr('href')).show();
                    }
                    if (!sessionStorage.getItem('ConversionTab[' + user_id + ']')) {
                        $('.unit_conversion_box input[value="imperial"]').prop('checked', true);

                    } else {
                        $('.unit_conversion_box input[value="' + sessionStorage.getItem('ConversionTab[' + user_id + ']') + '"]').prop('checked', true);
                        //calculation(sessionStorage.getItem('ConversionTab'));
                    }
                    /*Calculate imperial/metric SL */
                    // setTimeout(function(){
                    calculation($("input[name='conversion']:checked").val());
                    setTimeout(function () {
                        unit_temp = '';
                        $('.shopping-name-list').each(function () {
                            // console.log("extra input type",);
                            var recipe_ing = $(this).find('.recipeIngredientsServings').find('span.measure-value');
                            console.log("recipe_ing recipe_ing",recipe_ing);
                            var totalTemp = 0;
                            $(this).find('.ingredient-item-body').find('span.measure-value').each(function () {
                                calc_obj_temp = JSON.parse($(this).attr('data-amount_value'));
                                var temp = 0;
                                if (recipe_ing.attr('data-type') == '2') {
                                    temp = calc_for_liquid();
                                    var new_ing_value = '';
                                    if(temp >= 16 && temp <= 31.9){
                                        temp = temp * 0.0625;
                                        unit_temp = 'pints';
                                    }
                                    else if(temp >= 32 && temp <= 127.9){
                                        temp = temp * 0.03125;
                                        unit_temp = 'quarts';
                                    }
                                    else if (temp >= 128){
                                        temp = temp * 0.0078125;
                                        if(temp == 1){
                                            unit_temp = 'gallon';
                                        }
                                        else{
                                            unit_temp = 'gallons';
                                        }
                                    }
                                    else{
                                        unit_temp = 'fl oz';
                                    }
                                }
                                console.log("temp temp doc ready", temp);
                                totalTemp = parseFloat(temp) + parseFloat(totalTemp);
                            });
                            if(recipe_ing.attr('data-unit_format').text() == 'E1' || recipe_ing.attr('data-unit_format').text() == 'E2' || recipe_ing.attr('data-unit_format').text() == 'E3' || recipe_ing.attr('data-unit_format').text() == 'W1' || recipe_ing.attr('data-unit_format').text() == 'W2' || recipe_ing.attr('data-unit_format').text() == 'W3'){
                                $(this).find('span.unit_format').text(unit_temp);
                            }
                            if (recipe_ing.attr('data-type') == 2 && $(this).find('.extra_ingredient_input_number').attr('data-default') == 0) {
                                if (recipe_ing.attr('data-unit') == "metric") {
                                    if (totalTemp >= 1000) {
                                        totalTemp = parseFloat(totalTemp / 1000).toFixed(2);
                                        $(this).find('span.unit_format').text('l');
                                        $(this).find('.extra_ingredient_input_number').val(parseFloat(totalTemp).toFixed(2));
                                        $(this).find('.extra_ingredient_input_number').attr('value', parseFloat(totalTemp).toFixed(2));
                                    }
                                    $(this).find('.extra_ingredient_input_number').val(parseFloat(totalTemp).toFixed(2));
                                    $(this).find('.extra_ingredient_input_number').attr('value', parseFloat(totalTemp).toFixed(2));
                                    $(this).find('.extra_ingredient_input_number').attr('data-refresh_default_value', parseFloat(totalTemp).toFixed(2));
                                    $(this).find('.extra_ingredient_input_number').attr('data-refresh_updated_value', parseFloat(totalTemp).toFixed(2));
                                } else {
                                    $(this).find('.extra_ingredient_input_number').val(parseFloat(totalTemp).toFixed(2));
                                    $(this).find('.extra_ingredient_input_number').attr('value', parseFloat(totalTemp).toFixed(2));
                                    $(this).find('.extra_ingredient_input_number').attr('data-refresh_default_value', parseFloat(totalTemp).toFixed(2));
                                    $(this).find('.extra_ingredient_input_number').attr('data-refresh_updated_value', parseFloat(totalTemp).toFixed(2));
                                }
                            }

                            if ($(this).find('.extra_ingredient_input_number').attr('data-default') != 0) {
                                console.log("this this this", $(this).find('.serving_btn_new'));
                                $(this).find('.serving_btn_new').attr('disabled', 'disabled');
                            }
                        });
                    }, 1000);
                    // },400);


                    $('#delete_modal,#delete_place_modal,#delete_item_modal,#delete_note_modal,#clear_shopping_list_new').on('keyup', function (event) {
                        var keycode = (event.keyCode ? event.keyCode : event.which);
                        if (keycode == '13') {
                            $(this).find('.done_action_btn').click();
                            $(this).find('.done_action_btn').disabled();
                        }
                    });

                    // Form Validation for add note form
                    let addPlaceRules = {
                        place_name: {
                            required: true,
                            maxlength: 191,
                            remote: {
                                url: '{{ route('shoppinglist.checkplacename') }}',
                                type: 'post',
                                data: {
                                    _token: function () {
                                        return "{{ csrf_token() }}";
                                    },
                                    id: function () {
                                        return $('#frmAddPlace #place_id').val();
                                    },
                                    place_type: function () {
                                        return $('#frmAddPlace #place_type').val();
                                    },
                                }
                            }
                        },
                    };
                    let addPlaceMessage = {
                        place_name: {
                            required: "Place name is required",
                            remote: '{{ __('messages.ShoppingPlaceAlreadyUse') }}'

                        },
                    };
                    validateForm('#frmAddPlace', addPlaceRules, addPlaceMessage);
                    // Form Validation for add item form
                    let addItemRules = {
                        item_name: {
                            maxlength: 191,
                            required: true

                        },
                    };
                    let addItemMessage = {
                        item_name: {
                            required: "Item name is required",
                        },
                    };
                    validateForm('#frmAddItem', addItemRules, addItemMessage,'.other-item');


                    let addIngredientRules = {
                        autocomplete: {
                            required: true
                        },
                        measure_id: {
                            required: true
                        },
                        quantity: {
                            required: true,
                            min: 0.1,
                            max: 100
                        },
                        servings: {
                            // required: true,
                            min: 1,
                            max: 999
                        }
                    };
                    validateForm('#frmAddIngredient', addIngredientRules);

                    //new code for add-serving
                    $(document).on('click', '.add-serving', function (e) {
                        if ($(e.target).parents('.cheddar_clp').siblings('.shoppinglist-items').find('.extra_ingredient_input_number').attr('data-default') == 0) {
                            current_serving = $(this).parents('.servings_box').find('.serving_btn_new').val();
                            addedServing = parseFloat(current_serving) + 1;
                            if (addedServing > 0 && addedServing <= '{{ \App\Utils\AppConstant::SERVINGS_LIMIT }}') {
                                calculation_serving_update(addedServing, $(this).parents('.sl_amount_update'));
                                $(this).closest('.serving_box_new').find('.serving_btn_new').attr('data-serving', addedServing);
                                $(this).closest('.serving_box_new').find('.serving_btn_new').val(addedServing);
                                $(this).parents('.cheddar_clp').siblings('.shoppinglist-items').find('.extra_ingredient_input_number').attr('data-new_serving', addedServing);
                                let recipe_serving = $(e.target).closest('.sl_amount_update').find('.serving_btn_new').attr('data-serving');
                                let recipe_usersl_id = $(e.target).closest('.sl_amount_update').find('.serving_btn_new').attr('data-usersl-id');
                                let recipe_shoppingName = $(e.target).closest('.sl_amount_update').find('span.measure-value').attr('data-shoppingName');
                                let recipe_serving_list = {};
                                recipe_serving_list[recipe_usersl_id] = recipe_serving;

                                let ing_serving_list = {};
                                ing_serving_list[recipe_usersl_id] = recipe_serving;

                                let flag = 0;
                                let recipeIngredientsServings = '';
                                console.log("alert alert hasClass", $(e.target).closest('.sl_amount_update').find('#amount_update').hasClass('recipe_new_servings'));
                                var sn_that = $(e.target).parents('.shopping-name-list').find('.shoppinglist-items').find('.recipeIngredientsServings');
                                let recipe_extra_ing_amount = '';
                                let recipe_extra_ing_unit = '';
                                if ($(e.target).parents('.cheddar_clp').siblings('.shoppinglist-items').find('.extra_ingredient_input_number').val() != 0) {
                                    recipe_extra_ing_amount = $(e.target).parents('.cheddar_clp').siblings('.shoppinglist-items').find('.extra_ingredient_input_number');
                                    recipe_extra_ing_unit = $(e.target).parents('.cheddar_clp').siblings('.shoppinglist-items').find('span.unit_format');
                                }

                                if ($(e.target).closest('.sl_amount_update').find('#amount_update').hasClass('recipe_new_servings')) {
                                    flag = 1;
                                    recipeIngredientsServings = $(e.target).closest('.shopping-name-list').find('.recipeIngredientsServings');
                                    default_initial_value_add_serving = recipeIngredientsServings.find('span.measure-value').attr('data-default_initial_value');
                                    change_serving_update_new(sn_that, recipe_shoppingName, recipe_serving_list, recipeIngredientsServings, recipe_extra_ing_amount, recipe_extra_ing_unit, flag);
                                } else {
                                    flag = 0;
                                    recipeIngredientsServings = $(e.target).closest('.shopping-name-list').find('.recipeIngredientsServings');
                                    default_initial_value_add_serving = recipeIngredientsServings.find('span.measure-value').attr('data-default_initial_value');
                                    change_serving_update_new(sn_that, recipe_shoppingName, recipe_serving_list, recipeIngredientsServings, recipe_extra_ing_amount, recipe_extra_ing_unit, flag);
                                }
                                // setTimeout(() => {
                                var totalTemp = 0;
                                $(this).parents('.ingredient-item-body').find('span.measure-value').each(function () {
                                    calc_obj_temp = JSON.parse($(this).attr('data-amount_value'));
                                    var temp = 0;
                                    if ($(this).closest('.shopping-name-list').find('.recipeIngredientsServings').find('span.measure-value').attr('data-type') == '2') {
                                        temp = calc_for_liquid();
                                        unit_temp = '';
                                        if(temp >= 16 && temp <= 31.9){
                                            temp = temp * 0.0625;
                                            unit_temp = 'pints';
                                        }
                                        else if(temp >= 32 && temp <= 127.9){
                                            temp = temp * 0.03125;
                                            unit_temp = 'quarts';
                                        }
                                        else if (temp >= 128){
                                            temp = temp * 0.0078125;
                                            if(temp == 1){
                                                unit_temp = 'gallon';
                                            }
                                            else{
                                                unit_temp = 'gallons';
                                            }
                                        }
                                        else{
                                            unit_temp = 'fl oz';
                                        }
                                        $(e.target).parents('.cheddar_clp').siblings('.shoppinglist-items').find('span.unit_format').text(unit_temp);
                                    } else {
                                    }
                                    totalTemp = (parseFloat(temp) + parseFloat(totalTemp)).toFixed(2);
                                });
                                // extra_tag.val(totalTemp);
                                console.log("server add-serving", recipeIngredientsServings.find('span.measure-value').attr('data-type'));
                                if (recipeIngredientsServings.find('span.measure-value').attr('data-type') == '2') {
                                    if ($(e.target).parents('.cheddar_clp').siblings('.shoppinglist-items').find('.extra_ingredient_input_number').attr('data-default') != 0) {
                                        // let extra_ing_data=$(e.target).parents('.cheddar_clp').siblings('.shoppinglist-items').find('.extra_ingredient_input_number').attr('data-default');
                                        let new_extra_ing_data = $(e.target).parents('.cheddar_clp').siblings('.shoppinglist-items').find('.extra_ingredient_input_number').attr('data_newest_default');
                                        totalTemp = parseFloat(new_extra_ing_data) + parseFloat(totalTemp);
                                    }
                                    if (recipeIngredientsServings.find('span.measure-value').attr("data-unit") == "metric") {
                                        if (totalTemp >= 1000) {
                                            $(e.target).parents('.cheddar_clp').siblings('.shoppinglist-items').find('.extra_ingredient_input_number').attr('data-new_gmweight_liquid_result', totalTemp);
                                            totalTemp = parseFloat(totalTemp / 1000).toFixed(2);
                                            $(e.target).parents('.cheddar_clp').siblings('.shoppinglist-items').find('span.unit_format').text('l');
                                            $(e.target).parents('.cheddar_clp').siblings('.shoppinglist-items').find('.extra_ingredient_input_number').attr('value', totalTemp);
                                        } else {
                                            $(e.target).parents('.cheddar_clp').siblings('.shoppinglist-items').find('span.unit_format').text('ml');
                                        }
                                    }
                                    $(this).parents('.shopping-name-list').find('.extra_ingredient_input_number').val(totalTemp);
                                } else {

                                }
                                if(sn_that.find('span.measure-value').attr('data-unit_format') == "E1" || sn_that.find('span.measure-value').attr('data-unit_format') == "E2" || sn_that.find('span.measure-value').attr('data-unit_format') == "E3" ){
                                    var serving_quantity = $(e.target).closest('.sl_amount_update').find('span.measure-value').attr('data-quantity');
                                    sn_that.find('span.measure-value').attr('data-quantity',serving_quantity);
                                }
                                // console.log("input number input number",$(this).parents('.shopping-name-list').find('.extra_ingredient_input_number'));
                            }
                        }
                    });


                    $(document).on('click', '.sub-serving', function (e) {
                        if ($(e.target).parents('.cheddar_clp').siblings('.shoppinglist-items').find('.extra_ingredient_input_number').attr('data-default') == 0) {
                            current_serving = $(this).parents('.servings_box').find('.serving_btn_new').val();
                            subServing = parseFloat(current_serving) - 1;
                            if (subServing > 0 && subServing <= '{{ \App\Utils\AppConstant::SERVINGS_LIMIT }}') {
                                // alert($(this).parents('.sl_amount_update').find('span.measure-value').html());
                                calculation_serving_update(subServing, $(this).parents('.sl_amount_update'));
                                $(this).closest('.serving_box_new').find('.serving_btn_new').attr('data-serving', subServing);
                                $(this).closest('.serving_box_new').find('.serving_btn_new').val(subServing);
                                $(this).parents('.cheddar_clp').siblings('.shoppinglist-items').find('.extra_ingredient_input_number').attr('data-new_serving', subServing);
                                let recipe_serving = $(e.target).closest('.sl_amount_update').find('.serving_btn_new').attr('data-serving');
                                let recipe_usersl_id = $(e.target).closest('.sl_amount_update').find('.serving_btn_new').attr('data-usersl-id');
                                let recipe_shoppingName = $(e.target).closest('.sl_amount_update').find('span.measure-value').attr('data-shoppingName');

                                let recipe_serving_list = {};
                                recipe_serving_list[recipe_usersl_id] = recipe_serving;

                                let ing_serving_list = {};
                                ing_serving_list[recipe_usersl_id] = recipe_serving;

                                let flag = 0;
                                let recipeIngredientsServings = '';
                                console.log("alert alert hasClass", $(e.target).closest('.sl_amount_update').find('#amount_update').hasClass('recipe_new_servings'));
                                var sn_that = $(e.target).parents('.shopping-name-list').find('.shoppinglist-items').find('.recipeIngredientsServings');
                                let recipe_extra_ing_amount = '';
                                let recipe_extra_ing_unit = '';
                                if ($(e.target).parents('.cheddar_clp').siblings('.shoppinglist-items').find('.extra_ingredient_input_number').val() != 0) {
                                    recipe_extra_ing_amount = $(e.target).parents('.cheddar_clp').siblings('.shoppinglist-items').find('.extra_ingredient_input_number');
                                    recipe_extra_ing_unit = $(e.target).parents('.cheddar_clp').siblings('.shoppinglist-items').find('span.unit_format');
                                }

                                if ($(e.target).closest('.sl_amount_update').find('#amount_update').hasClass('recipe_new_servings')) {
                                    flag = 1;
                                    recipeIngredientsServings = $(e.target).closest('.shopping-name-list').find('.recipeIngredientsServings');
                                    // change_serving_update(recipe_shoppingName,recipe_serving_list,flag,recipeIngredientsServings,recipe_extra_ing_amount,recipe_extra_ing_unit,recipeIngredientsServings.find('span.measure-value').attr('data-default_initial_value'));
                                    default_initial_value_add_serving = recipeIngredientsServings.find('span.measure-value').attr('data-default_initial_value');
                                    change_serving_update_new(sn_that, recipe_shoppingName, recipe_serving_list, recipeIngredientsServings, recipe_extra_ing_amount, recipe_extra_ing_unit, flag);
                                } else {
                                    flag = 0;
                                    recipeIngredientsServings = $(e.target).closest('.shopping-name-list').find('.recipeIngredientsServings');
                                    // change_serving_update(recipe_shoppingName,ing_serving_list,flag,recipeIngredientsServings,recipe_extra_ing_amount,recipe_extra_ing_unit,recipeIngredientsServings.find('span.measure-value').attr('data-default_initial_value'));
                                    default_initial_value_add_serving = recipeIngredientsServings.find('span.measure-value').attr('data-default_initial_value');
                                    change_serving_update_new(sn_that, recipe_shoppingName, ing_serving_list, recipeIngredientsServings, recipe_extra_ing_amount, recipe_extra_ing_unit, flag);
                                }
                                // setTimeout(() => {
                                var totalTemp = 0;
                                $(this).parents('.ingredient-item-body').find('span.measure-value').each(function () {
                                    calc_obj_temp = JSON.parse($(this).attr('data-amount_value'));
                                    var temp = 0;
                                    if ($(this).closest('.shopping-name-list').find('.recipeIngredientsServings').find('span.measure-value').attr('data-type') == '2') {
                                        temp = calc_for_liquid();
                                        unit_temp = '';
                                        if(temp >= 16 && temp <= 31.9){
                                            temp = temp * 0.0625;
                                            unit_temp = 'pints';
                                        }
                                        else if(temp >= 32 && temp <= 127.9){
                                            temp = temp * 0.03125;
                                            unit_temp = 'quarts';
                                        }
                                        else if (temp >= 128){
                                            temp = temp * 0.0078125;
                                            if(temp == 1){
                                                unit_temp = 'gallon';
                                            }
                                            else{
                                                unit_temp = 'gallons';
                                            }
                                        }
                                        else{
                                            unit_temp = 'fl oz';
                                        }
                                        $(e.target).parents('.cheddar_clp').siblings('.shoppinglist-items').find('span.unit_format').text(unit_temp);
                                    } else {
                                    }
                                    totalTemp = (parseFloat(temp) + parseFloat(totalTemp)).toFixed(2);
                                });
                                if (recipeIngredientsServings.find('span.measure-value').attr('data-type') == '2') {
                                    if ($(e.target).parents('.cheddar_clp').siblings('.shoppinglist-items').find('.extra_ingredient_input_number').attr('data-default') != 0) {
                                        let new_extra_ing_data = $(e.target).parents('.cheddar_clp').siblings('.shoppinglist-items').find('.extra_ingredient_input_number').attr('data_newest_default');
                                        totalTemp = parseFloat(new_extra_ing_data) + parseFloat(totalTemp);
                                    }
                                    if (recipeIngredientsServings.find('span.measure-value').attr("data-unit") == "metric") {
                                        if (totalTemp >= 1000) {
                                            $(e.target).parents('.cheddar_clp').siblings('.shoppinglist-items').find('.extra_ingredient_input_number').attr('data-new_gmweight_liquid_result', totalTemp);
                                            totalTemp = parseFloat(totalTemp / 1000).toFixed(2);
                                            $(e.target).parents('.cheddar_clp').siblings('.shoppinglist-items').find('span.unit_format').text('l');
                                            $(e.target).parents('.cheddar_clp').siblings('.shoppinglist-items').find('.extra_ingredient_input_number').attr('value', totalTemp);
                                        } else {
                                            $(e.target).parents('.cheddar_clp').siblings('.shoppinglist-items').find('span.unit_format').text('ml');
                                        }

                                    }
                                    $(this).parents('.shopping-name-list').find('.extra_ingredient_input_number').val(totalTemp);
                                } else {

                                }
                                if(sn_that.find('span.measure-value').attr('data-unit_format') == "E1" || sn_that.find('span.measure-value').attr('data-unit_format') == "E2" || sn_that.find('span.measure-value').attr('data-unit_format') == "E3" ){
                                    var serving_quantity = $(e.target).closest('.sl_amount_update').find('span.measure-value').attr('data-quantity');
                                    sn_that.find('span.measure-value').attr('data-quantity',serving_quantity);
                                }
                            }
                        }
                    });

                    $(document).on('click', '.add-place,.clone-place', function () {
                        //console.log($(this).attr('data-title'));
                        $('#add_place #place_name').val($(this).attr('data-place_name'));
                        $('#add_place #place_id').val($(this).attr('data-place_id'));
                        $('#add_place #place_type').val($(this).attr('data-place_type'));
                        $('#add_place form').attr('action', $(this).attr('data-place_url'));
                        $('#add_place #AddPlace').text($(this).attr('data-title'));
                    });
                    $(document).on('click', '.check-ingredient', function () {
                        if($(this).prop('checked') == true){
                            $(this).closest('.sl-name').find('.measure-value').addClass('strike-through');
                        }else{
                            $(this).closest('.sl-name').find('.measure-value').removeClass('strike-through');
                        }

                        $.ajax({
                            url: "{{ route('check_ingredient') }}",
                            type: 'post',
                            dataType: "json",
                            data: {
                                ing_id: $(this).attr('data-sl_id'),
                                status: $(this).attr('data-status'),
                                _token: "{{ csrf_token() }}"
                            },
                            success: function (data) {
                                //console.log(data.meta.data=='success');
                                if (data.meta.data == 'success') {
                                    //SuccessNotify('Shopping name has been checked');
                                }
                            }
                        });
                    });
                    $(document).on('click', '.check-item', function () {
                        //console.log($(this).attr('data-title'));
                        if($(this).prop('checked') == true){
                            $(this).siblings('.ingredient-item-text').find('.measure-value').addClass('strike-through');
                        }else{
                            $(this).siblings('.ingredient-item-text').find('.measure-value').removeClass('strike-through');
                        }
                        var that = $(this);
                        $.ajax({
                            url: "{{ route('check_item') }}",
                            type: 'post',
                            dataType: "json",
                            data: {
                                item_id: $(this).attr('id'),
                                status: $(this).attr('data-status'),
                                _token: "{{ csrf_token() }}"
                            },
                            success: function (data) {
                                console.log("data",data);
                                if (data.meta.data == 'success') {
                                    // console.log("status1:" + data.meta.status_data);
                                    that.attr('data-status', data.meta.status_data);
                                    // console.log(data.meta.item_id);

                                    that.closest('.shoppinglist-items').find('.hide-button').toggleClass('invisible');
                                    // console.log("status2:"+data.meta.status_data);
                                    SuccessNotify('Item has been checked');
                                }
                            }
                        });
                    });

                    $(document).on('click', '.add-item', function () {
                        //console.log($(this).attr('data-title'));
                        $('.other-item').attr('disabled', false);
                        $('#add_item #shoppinglist_place_id').val($(this).attr('data-place_id'));
                        $('#add_item #item_name').val($(this).attr('data-item_name'));
                        var selectval = ($(this)).attr('data-place_id');
                        if (selectval) {
                            $('select[name^="aisle"] option:selected').attr("selected", false);
                            $('select[name^="aisle"] option[value="' + selectval + '"]').attr("selected", true);
                        } else {
                            $('select[name^="aisle"] option:selected').attr("selected", false);
                            $('select[name^="aisle"] option.other_option').attr("selected", true);
                        }
                        $('#add_item #item_id').val($(this).attr('data-item_id'));
                        $('#add_item #addItem').text($(this).attr('data-title'));
                    });


                    let imp_met = '';
                    $('input[type=radio][name=conversion]').change(function () {
                        var value = $("input[name='conversion']:checked").val();
                        sessionStorage.setItem('ConversionTab[' + user_id + ']', value);
                        if (value == 'metric') {
                            //for metric sl amount update
                            let recipe_metric_parentClass = '.ingredient-item-body .sl_unit';
                            let recipe_metric_childClass = '.recipe_new_servings';
                            calculate_metric_amount_update(recipe_metric_parentClass, recipe_metric_childClass);

                            let ing_metric_parentClass = '.ingredient-item-body .sl_unit';
                            let ing_metric_childClass = '.ing_new_servings';
                            calculate_metric_amount_update(ing_metric_parentClass, ing_metric_childClass);

                            $('#shopping_list .recipeIngredientsServings').each(function () {
                                var id = $(this).find('span.measure-value').attr('data-id');
                                var unit_format = $(this).find('span.measure-value').attr('data-unit_format');
                                var unit = $(this).find('span.measure-value').attr('data-unit');
                                var type = $(this).find('span.measure-value').attr('data-type');
                                var constant = $(this).find('span.measure-value').attr('data-constant');
                                var slname = $(this).find('span.measure-value').attr('data-name');
                                var quantity = $(this).find('span.measure-value').attr('data-quantity');
                                var gmweight = $(this).find('span.measure-value').attr('data-gmweight');
                                var each_weight = $(this).find('span.measure-value').attr('data-each_weight');
                                // var servings = $(this).find('span.measure-value').attr('data-serving');
                                var alter_name = $(this).find('span.measure-value').attr('data-alter_name');
                                var serving = $(this).closest('.sl-name').find('.extra_ingredient_input_number');
                                var constant = $(this).find('span.measure-value').attr('data-constant');
                                console.log("serving attr", serving.attr('data-default'));
                                var each_unit = 'kg';
                                var each = (gmweight/each_weight).toPrecision(1);
                                if (each_weight  >=  1000) {
                                    each_weight = (each_weight / 1000).toFixed(2);
                                    each_unit = 'kg';
                                } else {
                                    each_weight = toDecimal(each_weight.toString())
                                    each_unit = 'g';
                                }
                                if (serving.attr('data-default') != 0) {
                                    imp_met = 1;
                                }
                                // console.log("type metric constant serving this",$(this));
                                flag = 0;
                                let calc_amount_liquid = serving.val();
                                displayGmWeight = parseFloat(gmweight).toFixed(2);
                                // alert("displayGmWeight123"+displayGmWeight);
                                // console.log("displayGmWeight",displayGmWeight);
                                if (type == 1) {
                                    unit = 'g';
                                    console.log("serving", serving);
                                    if (gmweight >= 1000) {
                                        displayGmWeight = (gmweight / 1000).toFixed(2);
                                        unit = 'kg';
                                    }
                                    if(unit_format=='E1'){
                                        total=displayGmWeight;
                                        displayGmWeight = serving.val();
                                        unit = 'each';
                                    }
                                    if(unit_format=='E2'){
                                        // alert("displayGmWeight"+ displayGmWeight);
                                        total=displayGmWeight;
                                        // alert(total);
                                        totalunit = unit;
                                        displayGmWeight = serving.val();
                                        // alert(displayGmWeight);
                                        unit = 'each';
                                        final = slname+" ("+total+" "+totalunit+" total)";
                                        $(this).find('.measure-value').text(final);
                                    }
                                    if(unit_format=='E3'){
                                        total=displayGmWeight;
                                        totalunit = unit;
                                        displayGmWeight = serving.val();
                                        unit = 'each';
                                        final = slname+" ("+total+" "+totalunit+" total, "+each_gram+" "+each_unit+" each)";
                                        $(this).find('.measure-value').text(final);
                                    }
                                    if(unit_format=='W2'){
                                        alter_name = (alter_name!='')?" "+alter_name:'';
                                        final = displayGmWeight+" "+unit+" "+slname+" ("+each+alter_name+")";
                                        $(this).find('.measure-value').text(final);
                                    }
                                    if(unit_format=='W3'){
                                        alter_name = (alter_name!='')?" "+alter_name:'';
                                        final = slname+" ("+each+alter_name+", "+each_weight+" "+each_unit+" each)";
                                        $(this).find('.measure-value').text(final);
                                    }
                                    if(unit_format=='L2'){
                                        // displayGmWeight = each;
                                        // unit = 'each';
                                    }
                                } else {
                                    console.log("metric ", $(this).parents('.shopping-name-list').find('.ingredient-item-body span.measure-value'));
                                    let new_extra_result = calc_amount_liquid * 29.5735;
                                    displayGmWeight = new_extra_result.toFixed(2);
                                    $(this).closest('.sl-name').find('.extra_ingredient_input_number').attr('data-new_gmweight_liquid_result', displayGmWeight);
                                    unit = 'ml';
                                    if(unit_format=='E1'){
                                        total=displayGmWeight;
                                        displayGmWeight = serving.val();
                                        unit = 'each';
                                    }
                                    if(unit_format=='E2'){
                                        console.log("serving",serving);
                                        total=displayGmWeight;
                                        totalunit = unit;
                                        displayGmWeight = serving.val();
                                        unit = 'each';
                                        final = slname+" ("+total+" "+totalunit+" total)";
                                        $(this).find('.measure-value').text(final);
                                    }
                                    if(unit_format=='E3'){
                                        total=displayGmWeight;
                                        totalunit = unit;
                                        displayGmWeight = serving.val();
                                        unit = 'each';
                                        final = slname+" ("+total+" "+totalunit+" total, "+each_gram+" "+each_unit+" each)";
                                        $(this).find('.measure-value').text(final);
                                    }
                                    if(unit_format=='W2'){
                                        alter_name = (alter_name!='')?" "+alter_name:'';
                                        final = displayGmWeight+" "+unit+" "+slname+" ("+each+alter_name+")";
                                        $(this).find('.measure-value').text(final);
                                    }
                                    if(unit_format=='W3'){
                                        alter_name = (alter_name!='')?" "+alter_name:'';
                                        final = slname+" ("+each+alter_name+", "+each_weight+" "+each_unit+" each)";
                                        $(this).find('.measure-value').text(final);
                                    }
                                    if(unit_format=='L2' || unit_format == "L1"){
                                    
                                        if($(this).closest('.sl-name').find('span.unit_format').text() == 'pints'){
                                            displayGmWeight = serving.val();
                                            displayGmWeight = ((displayGmWeight * 16) * 29.5735).toFixed(2);
                                        }
                                        else if($(this).closest('.sl-name').find('span.unit_format').text() == 'quarts'){
                                            displayGmWeight = serving.val();
                                            displayGmWeight = ((displayGmWeight * 32) * 29.5735).toFixed(2);
                                        }
                                        else if($(this).closest('.sl-name').find('span.unit_format').text() == 'gallon' || $(this).closest('.sl-name').find('span.unit_format').text() == 'gallons'){
                                            displayGmWeight = serving.val();
                                            displayGmWeight = ((displayGmWeight * 128) * 29.5735).toFixed(2);
                                        }

                                    }
                                    if (displayGmWeight >= 1000) {
                                        displayGmWeight = (displayGmWeight / 1000).toFixed(2);
                                        unit = 'l';
                                    }
                                }
                                $(this).closest('.sl-name').find('.extra_ingredient_input_number').val(displayGmWeight);
                                $(this).closest('.sl-name').find('span.unit_format').text(unit);
                                // if($(this).closest('.sl-name').find('.extra_ingredient_input_number').attr('data-default') == 0){
                                //     $(this).find('span.measure-value').attr('data-default_initial_value',$(this).find('span.measure-value').attr('data-default_gmweight'));
                                // }
                                // convertSLToGram(type, gmweight, each_weight, slname, unit_format, alter_name, $(this), serving,imp_met);

                                $(this).find('span.measure-value').attr('data-unit', 'metric');
                                $('.ingredient-item-body .sl_unit').find('span.measure-value').attr('data-unit', 'metric');
                                // console.log("SL unit sl unit SL unit sl unit",$('div.sl_unit').find('span.measure-value'));

                                // }
                            });


                        } else {
                            //for imperial sl amount update
                            let recipe_imperial_parentClass = '.ingredient-item-body .sl_unit';
                            let recipe_imperial_childClass = '.recipe_new_servings';
                            calculate_imperial_amount_update(recipe_imperial_parentClass, recipe_imperial_childClass);

                            let ing_imperial_parentClass = '.ingredient-item-body .sl_unit';
                            let ing_imperial_childClass = '.ing_new_servings';
                            calculate_imperial_amount_update(ing_imperial_parentClass, ing_imperial_childClass);

                            $('#shopping_list .recipeIngredientsServings').each(function () {
                                var id = $(this).find('span.measure-value').attr('data-id');
                                var unit_format = $(this).find('span.measure-value').attr('data-unit_format');
                                var unit = $(this).find('span.measure-value').attr('data-unit');
                                var type = $(this).find('span.measure-value').attr('data-type');
                                var constant = $(this).find('span.measure-value').attr('data-constant');
                                var slname = $(this).find('span.measure-value').attr('data-name');
                                var quantity = $(this).find('span.measure-value').attr('data-quantity');
                                var gmweight = $(this).find('span.measure-value').attr('data-gmweight');
                                var each_weight = $(this).find('span.measure-value').attr('data-each_weight');
                                // var servings = $(this).find('span.measure-value').attr('data-servings');
                                var alter_name = $(this).find('span.measure-value').attr('data-alter_name');
                                var serving = $(this).closest('.sl-name').find('.extra_ingredient_input_number');
                                var each = (gmweight/each_weight).toPrecision(1);
                                each_unit = 'oz';
                                // if ($(this).find('span.measure-value').attr('data-unit') == 'metric') {
                                // var is_fraction = $(this).find('span.measure-value').attr('data-fraction');
                                /*  var serving = 0;
                                  var updated_serving = Number($('.servings-btn').attr('data-serving'));
                                  var constant = $(this).find('span.measure-value').attr('data-constant');
                                  var type = $(this).find('span.measure-value').attr('data-type');
                                  var value = ($(this).find('span.measure-value').attr('data-original') * updated_serving) / serving;*/
                                // alert("metric");
                                if (parseFloat(each_weight).toFixed(2)  >=  450.71) {
                                    each_weight = (each_weight / 453.6 ).toFixed(2);
                                    each_unit = 'lb';
                                } else {
                                    each_weight = (each_weight / 28.35).toFixed(2);
                                    each_unit = 'oz';
                                }
                                if (serving.attr('data-default') != 0) {
                                    imp_met = 0;
                                }
                                console.log("imperial constant serving", type, 'imperial', constant, serving, $(this));
                                // alert("calc_amount_for_liquid");
                                // let calc_amount_liquid=calc_amount_for_liquid(type,'imperial',constant,serving,$(this));
                                let calc_amount_liquid = serving.val();
                                displayGmWeight = parseFloat(gmweight).toFixed(2);
                                console.log("imperial displayGmweight", displayGmWeight);
                                if (type == 1) {
                                    if (gmweight >= 450.71) {
                                        // alert("gmweight if imperial"+gmweight);
                                        displayGmWeight = (gmweight / 453.6).toFixed(2);
                                        unit = 'lb';
                                        // that.find('span.measure_unit').text('lb');
                                    } else {
                                        // alert("gmweight else imperial"+gmweight);
                                        displayGmWeight = (gmweight / 28.35).toFixed(2);
                                        unit = 'oz';
                                        // that.find('span.measure_unit').text('oz');
                                    }
                                    if(unit_format=='E1'){
                                        total = displayGmWeight;
                                        displayGmWeight = serving.val();
                                        unit = 'each';
                                    }
                                    if(unit_format=='E2'){
                                        total = displayGmWeight;
                                        // alert(total);
                                        totalunit = unit;
                                        displayGmWeight = serving.val();
                                        unit = 'each';
                                        final = slname+" ("+total+" "+totalunit+" total)";
                                        $(this).find('.measure-value').text(final);
                                    }
                                    if(unit_format=='E3'){
                                        total = displayGmWeight;
                                        totalunit = unit;
                                        displayGmWeight = serving.val();
                                        unit = 'each';
                                        final = slname+" ("+total+" "+unit+" total, "+each_gram+" "+each_unit+" each)";
                                        $(this).find('.measure-value').text(final);
                                    }
                                    if(unit_format=='W2'){
                                        alter_name = (alter_name!='')?" "+alter_name:'';
                                        final = displayGmWeight+" "+unit+" "+slname+" ("+each+alter_name+")";
                                        $(this).find('.measure-value').text(final);
                                    }
                                    if(unit_format=='W3'){
                                        alter_name = (alter_name!='')?" "+alter_name:'';
                                        final = slname+" ("+each+alter_name+", "+each_weight+" "+each_unit+" each)";
                                        $(this).find('.measure-value').text(final);
                                    }
                                    if(unit_format=='L2'){
                                        total = displayGmWeight;
                                        totalunit = unit;
                                        displayGmWeight = each;
                                        unit = 'each';
                                    }
                                } else {
                                    // var new_serving_val =  serving.val()
                                    //need to be change after add-serving calcualtion refactor
                                    let new_extra_result = calc_amount_liquid / 29.5735;

                                    // $(this).closest('.sl-name').find('.extra_ingredient_input_number').attr('data-new_gmweight_liquid_result',new_extra_result);
                                    displayGmWeight = new_extra_result.toFixed(2);
                                    
                                    // unit = 'fl oz';
                                    if ($(this).closest('.sl-name').find('span.unit_format').text() == 'l') {
                                        // displayGmWeight = ($(this).closest('.sl-name').find('.extra_ingredient_input_number').attr('data-new_gmweight_liquid_result') / 29.5735).toFixed(2);                                    
                                        displayGmWeight=(( calc_amount_liquid * 1000) / 29.5725).toFixed(2);
                                    }
                                    if(displayGmWeight >= 16 && displayGmWeight <= 31.9){
                                        displayGmWeight = (displayGmWeight * 0.0625).toFixed(2);
                                        unit = 'pints';
                                    }
                                    else if(displayGmWeight >= 32 && displayGmWeight <= 127.9){
                                        displayGmWeight = (displayGmWeight * 0.03125).toFixed(2);
                                        unit = 'quarts';
                                    }
                                    else if (displayGmWeight >= 128){
                                        displayGmWeight = (displayGmWeight * 0.0078125).toFixed(2);
                                        if(displayGmWeight == 1){
                                            unit = 'gallon';
                                        }
                                        else{
                                            unit = 'gallons';
                                        }
                                    }
                                    else{
                                        unit = 'fl oz';
                                    }
                                }
                                $(this).closest('.sl-name').find('.extra_ingredient_input_number').val(displayGmWeight);
                                $(this).closest('.sl-name').find('span.unit_format').text(unit);
                                console.log("displayGmWeight", displayGmWeight);
                                // if($(this).closest('.sl-name').find('.extra_ingredient_input_number').attr('data-default') == 0){
                                //     $(this).find('span.measure-value').attr('data-default_initial_value',displayGmWeight);
                                // }
                                // convertToImperialSolid(type, gmweight, each_weight, slname, unit_format, alter_name, $(this),serving,imp_met);
                                $(this).find('span.measure-value').attr('data-unit', 'imperial');
                                $('.ingredient-item-body .sl_unit').find('span.measure-value').attr('data-unit', 'imperial');
                                // }
                            });

                        }
                        // console.log("sl_unit sl_unit",$('ingredient-item-body .sl_unit'));
                    });

                    $('.recipe_list_pills').on('shown.bs.tab', function (event) {
                        $('.unit_conversion_box').addClass('d-none');
                    });
                    $(document).on('click', '.sl-list', function (event) {
                        //console.log('dd');
                        sessionStorage.setItem('Sltab', $(this).attr('data-id'));
                    });
                    $(document).on('click', '.delete-sl-record', function (event) {
                        $('#delete_modal #uuid').val($(this).attr('data-sl_id'));
                        // $('#delete_modal #uuid').val($(this).attr('data-sl_id'));
                        $('#delete_modal #type').val($(this).attr('data-type'));
                        $('#delete_modal #deleteCollectionName').text($(this).attr('data-header'));
                        $('#delete_modal #deleteItem').text($(this).attr('data-message'));
                        $('#delete_modal #usld_id').val($(this).attr('data-usld_id'));
                    });
                    $('.SL-Collapse').on('click', function (event) {
                        getSLInfo(this);
                    });

                    $(".SL-Collapse").not('.collapsed').each(function () {
                        getSLInfo(this);
                    });
                    $('.shopping_list_pills').on('shown.bs.tab', function (event) {
                        if ($('.unit_conversion_box').has('.d-none')) {
                            $('.unit_conversion_box').removeClass('d-none');
                        }
                    });

                    $("#sortable").sortable({
                        connectWith: '.hideHeaderShoppingList',
                        handle: '.white-drag-icon',
                        opacity: 0.6,
                        update: function (event, ui) {
                            var Order = $("#sortable").sortable('toArray').toString();

                            $('#order').val(Order);
                            var values = [];
                            $('.dairyAccordion').each(function () {
                                var value = $(this).attr('data-placeid');
                                if (value != undefined) {
                                    values.push(value);
                                }
                            });
                            updateOrder(values);

                        }
                    });
                    $(".sortable").sortable({
                        connectWith: '.shopping-name-list,.shoppinglist-items',
                        handle: '.grey-drag-icon',
                        opacity: 0.6,
                        update: function (event, ui) {
                            var Order = $(".sortable").sortable('toArray').toString();
                            $('#order').val(Order);

                            var values = [];
                            $('#' + $(this).attr('id')).find('.shoppping_items_id').each(function (i, e) {

                                var value1 = $(this).attr('data-id');
                                var value2 = $(this).attr('data-type');
                                // console.log(value);
                                if (values != undefined) {
                                    values.push({
                                        type: value2,
                                        o_id: value1,
                                    });
                                }
                            });
                            // console.log(values);
                            updateInnerOrder(values);
                        }
                    });
                });

                function updateOrder(uuids) {
                    axios({
                        url: '{{ route('shoppinglist.update_order') }}',
                        method: 'POST',
                        data: {
                            data: uuids,
                        },
                    }).then(function (response) {
                        if (response.data.meta.status) {
                            //SuccessNotify(response.data.meta.message);
                        } else {
                            //ErrorNotify(response.data.meta.message);
                        }

                    });
                }

                function updateInnerOrder(uuids) {
                    axios({
                        url: '{{ route('shoppinglist.update_inner_order') }}',
                        method: 'POST',
                        data: {
                            data: uuids,
                        },
                    }).then(function (response) {
                        if (response.data.meta.status) {
                            //SuccessNotify(response.data.meta.message);
                        } else {
                            // ErrorNotify(response.data.meta.message);
                        }

                    });
                }


                function handleRotateIcon(e) {
                    $.ajax({
                        url: '{{ route('shoppinglist.collapse_section') }}',
                        type: 'post',
                        //dataType: "json",
                        data: {
                            is_collapse: $(e).attr("aria-expanded"),
                            type: $(e).attr('data-is_type'),
                            place_id: $(e).attr('data-place_id'),
                            s_name: $(e).attr('data-s_name'),
                            //id: $(this).closest('.accordion').attr('data-id'),
                            _token: "{{ csrf_token() }}"
                        },
                        success: function (response) {
                            // console.log(response);
                        }
                    });
                }

                function getSLInfo(that) {
                    console.log("that that", $(that).attr('data-sl_id'));
                    if ($(that).attr('data-ajax') != 'fetch') {
                        var id = $(that).closest('.shopping-name-list').attr('data-id');
                        getPlaceDetail(id, $(that).attr('data-place_id'), $(that).attr('data-sl_id'), 1, $(that));
                        getPlaceDetail(id, $(that).attr('data-place_id'), $(that).attr('data-sl_id'), 0, $(that));
                        $(that).attr('data-ajax', 'fetch');
                    }
                }

                var child_temp = '';
                var new_childamount_update1 = '';
                var new_childamount_update2 = '';

                function getPlaceDetail(id, place_id, sl_id, type = "recipe", that, url = "{{ route('get_user_shopping_list') }}") {
                    let unit_t = $("input[name='conversion']:checked").val();
                    $.ajax({
                        url: url,
                        type: 'post',
                        // dataType: "json",
                        data: {
                            place_id: place_id,
                            sl_id: sl_id,
                            type: type,
                            unit_type: unit_t,
                            //id: $(this).closest('.accordion').attr('data-id'),
                            _token: "{{ csrf_token() }}"
                        },
                        beforeSend: function () {
                            $('.ingredient-item-body #chedderCheeseRecipeCollapse_' + id).html('<div class="text-center m-auto recipe-spin" style="position: relative; top: 40%;"><i class="fa fa-spin fa-spinner fa-2x"></i> </div>');
                            $('.ingredient-item-body #chedderCheeseIngredientCollapse_' + id).html('<div class="text-center m-auto ing-spin" style="position: relative; top: 40%;"><i class="fa fa-spin fa-spinner fa-2x"></i> </div>');
                        },
                        success: function (response) {
                            if (type == 1) {
                                console.log("success");
                                if ($.trim(response) && response != null) {
                                    var unit = $("input[name='conversion']:checked").val();
                                    var parentClass = '.ingredient-item-body #chedderCheeseRecipeCollapse_';
                                    var childClass = '.recipe_new_servings'
                                    $(parentClass + id).html(response);
                                    //for amount update issue...
                                    new_childamount_update1 = calculation_amount_update(unit, id, parentClass, childClass);
                                } else {
                                    console.log("response response", response);
                                    $('.recipe-spin').addClass('d-none');
                                    $(document).find('.sl-recipe-title' + id).remove();
                                }

                            } else {
                                if ($.trim(response) && response != null) {
                                    var unit = $("input[name='conversion']:checked").val();
                                    var parentClass = '.ingredient-item-body #chedderCheeseIngredientCollapse_';
                                    var childClass = '.ing_new_servings'
                                    $('.ing-spin').addClass('d-none');
                                    $(parentClass + id).find('.ingredident-name').remove();
                                    $(parentClass + id).prepend(response);

                                    //for amount update issue...
                                    // calculation_amount_update(unit,id,parentClass,childClass);
                                    new_childamount_update2 = calculation_amount_update(unit, id, parentClass, childClass);
                                } else {
                                    $('.ing-spin').addClass('d-none');
                                    // $('.ingredient-item-body #chedderCheeseIngredientAccordion_' + id).find('.sl-extra-ing-title'+id).remove();
                                    // $('.sl-extra-ing-title'+id).addClass('d-none');
                                    $(document).find('.sl-extra-ing-title' + id).remove();
                                }
                                child_temp = new_childamount_update1 + new_childamount_update2;
                                return child_temp;

                            }
                        }
                    });
                }

                //new getPlaceDetail for Doc ready
                var child_temp_doc = '';
                var new_childamount_update_doc1 = '';
                var new_childamount_update_doc2 = '';

                function getPlaceDetail_for_doc_ready(id, place_id, sl_id, type = "recipe", that, url = "{{ route('get_user_shopping_list') }}") {
                    let unit_t = $("input[name='conversion']:checked").val();
                    $.ajax({
                        url: url,
                        type: 'post',
                        // dataType: "json",
                        data: {
                            place_id: place_id,
                            sl_id: sl_id,
                            type: type,
                            unit_type: unit_t,
                            _token: "{{ csrf_token() }}"
                        },
                        success: function (response) {
                            if (type == 1) {
                                if ($.trim(response) && response != null) {
                                    // alert("recipe response");
                                    var unit = $("input[name='conversion']:checked").val();
                                    var parentClass = '.ingredient-item-body #chedderCheeseRecipeCollapse_';
                                    var childClass = '.recipe_new_servings'
                                    $(parentClass + id).html(response);
                                    //for amount update issue...
                                    new_childamount_update_doc1 = calculation_amount_update(unit, id, parentClass, childClass);
                                } else {
                                    //  console.log("response response",response);
                                    // $('.recipe-spin').addClass('d-none');
                                    // $(document).find('.sl-recipe-title'+id).remove();
                                }

                            } else {
                                if ($.trim(response) && response != null) {
                                    var unit = $("input[name='conversion']:checked").val();
                                    var parentClass = '.ingredient-item-body #chedderCheeseIngredientCollapse_';
                                    var childClass = '.ing_new_servings'
                                    // $('.ing-spin').addClass('d-none');
                                    $(parentClass + id).find('.ingredident-name').remove();
                                    $(parentClass + id).prepend(response);

                                    //for amount update issue...
                                    new_childamount_update_doc2 = calculation_amount_update(unit, id, parentClass, childClass);
                                } else {
                                    // $('.ing-spin').addClass('d-none');
                                    // $('.ingredient-item-body #chedderCheeseIngredientAccordion_' + id).find('.sl-extra-ing-title'+id).remove();
                                    // $('.sl-extra-ing-title'+id).addClass('d-none');
                                    // $(document).find('.sl-extra-ing-title'+id).remove();
                                }
                                child_temp_doc = new_childamount_update_doc1 + new_childamount_update_doc2;

                                return child_temp_doc;

                            }
                            // console.log("new",new_childamount_update1);
                        }
                    });
                }

                function calculation(tab, that, isReady = 0) {
                    if (tab == 'imperial') {
                        var id = '';
                        var type = '';
                        var constant = '';
                        var serving = '';
                        $('#shopping_list .recipeIngredientsServings').each(function () {
                            id = $(this).find('span.measure-value').attr('data-id');
                            var unit = $(this).find('span.measure-value').attr('data-unit');
                            var unit_format = $(this).find('span.measure-value').attr('data-unit_format');
                            type = $(this).find('span.measure-value').attr('data-type');
                            constant = $(this).find('span.measure-value').attr('data-constant');
                            var slname = $(this).find('span.measure-value').attr('data-name');
                            var quantity = $(this).find('span.measure-value').attr('data-servings');
                            var gmweight = $(this).find('span.measure-value').attr('data-gmweight');
                            var each_weight = $(this).find('span.measure-value').attr('data-each_weight');
                            var alter_name = $(this).find('span.measure-value').attr('data-alter_name');
                            serving = $(this).closest('.sl-name').find('.extra_ingredient_input_number');
                            var place_id = $(this).parents('.shoppinglist-items').find('.SL-Collapse').attr('data-place_id');
                            var sl_id = $(this).parents('.shoppinglist-items').find('.SL-Collapse').attr('data-sl_id');
                            var temp_sl_serving = $(serving).closest('.sl-name').find('.recipeIngredientsServings').find('span.measure-value').attr('data-default_initial_value', $(serving).val());
                            if ($(this).closest('.sl-name').siblings('.align-items-center').find('.collapse-icon-width').find('button').hasClass('collapsed') && type == 2) {
                                getPlaceDetail_for_doc_ready(id, place_id, sl_id, 1, $(this));
                                getPlaceDetail_for_doc_ready(id, place_id, sl_id, 0, $(this));
                            }
                            console.log("paretns", $(this).parents('.shopping-name-list').find('.ingredient-item-body').find('.ingredient_accordion').find('.sl_unit'));
                            console.log("that that that addserving", that);
                            convertToImperialSolid(type, gmweight, each_weight, slname, unit_format, alter_name, $(this), serving);
                            console.log("serving serving", serving.val());
                            $(this).find('span.measure-value').attr('data-unit', 'imperial');
                        });
                    } else {
                        $('#shopping_list .recipeIngredientsServings').each(function () {
                            var id = $(this).find('span.measure-value').attr('data-id');
                            var unit = $(this).find('span.measure-value').attr('data-unit');
                            var unit_format = $(this).find('span.measure-value').attr('data-unit_format');
                            var type = $(this).find('span.measure-value').attr('data-type');
                            var constant = $(this).find('span.measure-value').attr('data-constant');
                            var slname = $(this).find('span.measure-value').attr('data-name');
                            var quantity = $(this).find('span.measure-value').attr('data-servings');
                            var gmweight = $(this).find('span.measure-value').attr('data-gmweight');
                            var each_weight = $(this).find('span.measure-value').attr('data-each_weight');
                            var alter_name = $(this).find('span.measure-value').attr('data-alter_name');
                            var extra_ing = $(this).closest('.collapse').find('.extra_ingredient_input').val();
                            var place_id = $(this).parents('.shoppinglist-items').find('.SL-Collapse').attr('data-place_id');
                            var sl_id = $(this).parents('.shoppinglist-items').find('.SL-Collapse').attr('data-sl_id');
                            var serving = $(this).closest('.sl-name').find('.extra_ingredient_input_number');
                            flag = 0;
                            if ($(this).closest('.sl-name').siblings('.align-items-center').find('.collapse-icon-width').find('button').hasClass('collapsed') && type == 2 && serving.attr('data-default') == 0) {
                                getPlaceDetail_for_doc_ready(id, place_id, sl_id, 1, $(this));
                                getPlaceDetail_for_doc_ready(id, place_id, sl_id, 0, $(this));
                            }
                            console.log("console log", serving.attr('data-default'));
                            convertSLToGram(type, gmweight, each_weight, slname, unit_format, alter_name, $(this), serving);
                            console.log("after console log", serving.attr('data-default'));

                            $(this).find('span.measure-value').attr('data-unit', 'metric');
                        });
                    }

                }

                function calculation_new(tab, that) {
                    console.log("that", that);
                    if (tab == 'imperial') {
                        var id = '';
                        var type = '';
                        var constant = '';
                        var serving = '';
                        id = that.find('span.measure-value').attr('data-id');
                        var unit = that.find('span.measure-value').attr('data-unit');
                        var unit_format = that.find('span.measure-value').attr('data-unit_format');
                        type = that.find('span.measure-value').attr('data-type');
                        constant = that.find('span.measure-value').attr('data-constant');
                        var slname = that.find('span.measure-value').attr('data-name');
                        var quantity = that.find('span.measure-value').attr('data-servings');
                        var gmweight = that.find('span.measure-value').attr('data-gmweight');
                        var each_weight = that.find('span.measure-value').attr('data-each_weight');
                        var alter_name = that.find('span.measure-value').attr('data-alter_name');
                        serving = that.closest('.sl-name').find('.extra_ingredient_input_number');
                        var place_id = that.parents('.shoppinglist-items').find('.SL-Collapse').attr('data-place_id');
                        var sl_id = that.parents('.shoppinglist-items').find('.SL-Collapse').attr('data-sl_id');
                        var temp_sl_serving = $(serving).closest('.sl-name').find('.recipeIngredientsServings').find('span.measure-value').attr('data-default_initial_value', $(serving).val());
                        // var old_serving = serving.attr('data-old_serving');
                        // var new_serving = serving.attr('data-new_serving');
                        // var new_quantity = that.find('span.measure-value');
                        // var calc_quantity = (new_quantity.attr('data-quantity') * new_serving) / old_serving ;
                        // console.log(calc_quantity);
                        // new_quantity.attr('data-quantity', calc_quantity);

                        convertToImperialSolid_new(type, gmweight, each_weight, slname, unit_format, alter_name, that, serving);
                        that.find('span.measure-value').attr('data-unit', 'imperial');
                    } else {
                        $('#shopping_list .recipeIngredientsServings').each(function () {
                            var id = that.find('span.measure-value').attr('data-id');
                            var unit = that.find('span.measure-value').attr('data-unit');
                            var unit_format = that.find('span.measure-value').attr('data-unit_format');
                            var type = that.find('span.measure-value').attr('data-type');
                            var constant = that.find('span.measure-value').attr('data-constant');
                            var slname = that.find('span.measure-value').attr('data-name');
                            var quantity = that.find('span.measure-value').attr('data-servings');
                            var gmweight = that.find('span.measure-value').attr('data-gmweight');
                            var each_weight = that.find('span.measure-value').attr('data-each_weight');
                            var alter_name = that.find('span.measure-value').attr('data-alter_name');
                            var extra_ing = that.closest('.collapse').find('.extra_ingredient_input').val();
                            var serving = that.closest('.sl-name').find('.extra_ingredient_input_number');
                            flag = 0;
                            let calc_amount_liquid = calc_amount_for_liquid(type, 'imperial', constant, serving, that, flag);
                            console.log("calc_amount_liquid", calc_amount_liquid);
                            //for solid
                            convertSLToGram_new(type, gmweight, each_weight, slname, unit_format, alter_name, that, serving);
                            that.find('span.measure-value').attr('data-unit', 'metric');
                        });
                    }

                }


                function handleCheckedInput(toggleCheckedItem) {
                    let forLength = $('.hideHeaderShoppingList').length;
                    let acordanceCheckedLength;
                    let acordanceUnCheckLength;
                    for (let i = 1; i <= forLength; i++) {
                        acordanceCheckedLength = $('#place_' + i).find('input:checked').length
                        acordanceUnCheckLength = $('#place_' + i).find('input[type=checkbox]').length
                        if (toggleCheckedItem) {
                            if (acordanceCheckedLength === acordanceUnCheckLength) {
                                $('#place_' + i).addClass('d-none')
                            }
                        } else {
                            $('#place_' + i).removeClass('d-none')
                        }
                    }
                    if (toggleCheckedItem) {
                        $(".ingredient_list_accordion").find('input:checked').closest('.shopping-name-list').addClass("d-none");
                        $(".ingredient_list_accordion").find('input:checked').closest('.ingredient_list_accordion').addClass("d-none");
                        toggleCheckedItem = !toggleCheckedItem;
                    } else {
                        $(".ingredient_list_accordion").find('input:checked').closest('.shopping-name-list').removeClass("d-none");
                        $(".ingredient_list_accordion").find('input:checked').closest('.shopping-name-list').removeClass("d-none");
                        $(".ingredient_list_accordion").find('input:checked').closest('.ingredient_list_accordion').removeClass("d-none");
                        toggleCheckedItem = !toggleCheckedItem;

                    }

                }

                $(document).on('click', '#deleteShoppingList', function () {
                    var did = $(this).attr('data-shoppingname');
                    $("#delete_shoppingName").val(did);
                });


                $('.print_checkbox').click(function () {
                    if ($(this).is(':checked') && $(this).val() == 'all') {
                        if ($(this).hasClass('poptions')) {
                            $('.poptions').prop('checked', true);
                        }
                    } else if ($(this).val() == 'all') {
                        if ($(this).hasClass('poptions')) {
                            $('.poptions').prop('checked', false);
                        }

                    } else if (!$(this).is(':checked')) {
                        if ($(this).hasClass('poptions')) {
                            $('.print-options').find("input[value='all']").prop('checked', false);
                        }
                    }
                    //for al checked then 'all' checkbox will be checked
                    if ($('.print-options').find("input[type='checkbox']:checked").length === $('.print-options').find("input[type='checkbox']").length - 1) {
                        $('.print-options').find("input[value='all']").prop('checked', true);
                    } else if ($('.person-family').find("input[type='checkbox']:checked").length === $('.person-family').find("input[type='checkbox']").length - 1) {
                        $('.person-family').find("input[value='all']").prop('checked', true);
                    }
                });

                $(document).ready(function () {
                    let printRules = {
                        "options[]": {
                            required: true
                        },
                    };
                    let printMsg = {
                        "options[]": {
                            required: "Check atleast one checkbox to print result..!"
                        },
                    };
                    validateForm('#printOptions', printRules, printMsg,'print_modal');
                });


                //new code
                $(document).ready(function () {
                    $(document).on('paste keyup', '.serving_btn_new', function (e) {
                        if ($(e.target).parents('.cheddar_clp').siblings('.shoppinglist-items').find('.extra_ingredient_input_number').attr('data-default') == 0) {
                            var oldServing = $(this).attr('data-serving');
                            var newServing = $(this).val();

                            if (newServing >= 1 && newServing !== null) {
                                calculation_serving_update(newServing, $(this).parents('.sl_amount_update'));
                                $(this).closest('.serving_box_new').find('.serving_btn_new').attr('data-serving', newServing);
                                $(this).closest('.serving_box_new').find('.serving_btn_new').val(newServing);
                                $(this).parents('.cheddar_clp').siblings('.shoppinglist-items').find('.extra_ingredient_input_number').attr('data-new_serving', newServing);
                                let recipe_serving = $(e.target).closest('.sl_amount_update').find('.serving_btn_new').attr('data-serving');
                                let recipe_usersl_id = $(e.target).closest('.sl_amount_update').find('.serving_btn_new').attr('data-usersl-id');
                                let recipe_shoppingName = $(e.target).closest('.sl_amount_update').find('span.measure-value').attr('data-shoppingName');

                                let recipe_serving_list = {};
                                recipe_serving_list[recipe_usersl_id] = recipe_serving;

                                let ing_serving_list = {};
                                ing_serving_list[recipe_usersl_id] = recipe_serving;

                                let flag = 0;
                                let recipeIngredientsServings = '';
                                console.log("alert alert hasClass", $(e.target).closest('.sl_amount_update').find('#amount_update').hasClass('recipe_new_servings'));
                                var sn_that = $(e.target).parents('.shopping-name-list').find('.shoppinglist-items').find('.recipeIngredientsServings');
                                let recipe_extra_ing_amount = '';
                                let recipe_extra_ing_unit = '';
                                if ($(e.target).parents('.cheddar_clp').siblings('.shoppinglist-items').find('.extra_ingredient_input_number').val() != 0) {
                                    recipe_extra_ing_amount = $(e.target).parents('.cheddar_clp').siblings('.shoppinglist-items').find('.extra_ingredient_input_number');
                                    recipe_extra_ing_unit = $(e.target).parents('.cheddar_clp').siblings('.shoppinglist-items').find('span.unit_format');
                                }

                                if ($(e.target).closest('.sl_amount_update').find('#amount_update').hasClass('recipe_new_servings')) {
                                    flag = 1;
                                    recipeIngredientsServings = $(e.target).closest('.shopping-name-list').find('.recipeIngredientsServings');
                                    default_initial_value_add_serving = recipeIngredientsServings.find('span.measure-value').attr('data-default_initial_value');
                                    change_serving_update_new(sn_that, recipe_shoppingName, recipe_serving_list, recipeIngredientsServings, recipe_extra_ing_amount, recipe_extra_ing_unit, flag);
                                } else {
                                    flag = 0;
                                    recipeIngredientsServings = $(e.target).closest('.shopping-name-list').find('.recipeIngredientsServings');
                                    default_initial_value_add_serving = recipeIngredientsServings.find('span.measure-value').attr('data-default_initial_value');
                                    change_serving_update_new(sn_that, recipe_shoppingName, ing_serving_list, recipeIngredientsServings, recipe_extra_ing_amount, recipe_extra_ing_unit, flag);
                                }
                                var totalTemp = 0;
                                $(this).parents('.ingredient-item-body').find('span.measure-value').each(function () {
                                    calc_obj_temp = JSON.parse($(this).attr('data-amount_value'));
                                    var temp = 0;
                                    if ($(this).closest('.shopping-name-list').find('.recipeIngredientsServings').find('span.measure-value').attr('data-type') == '2') {
                                        temp = calc_for_liquid();
                                        unit_temp = '';
                                        if(temp >= 16 && temp <= 31.9){
                                            temp = temp * 0.0625;
                                            unit_temp = 'pints';
                                        }
                                        else if(temp >= 32 && temp <= 127.9){
                                            temp = temp * 0.03125;
                                            unit_temp = 'quarts';
                                        }
                                        else if (temp >= 128){
                                            temp = temp * 0.0078125;
                                            if(temp == 1){
                                                unit_temp = 'gallon';
                                            }
                                            else{
                                                unit_temp = 'gallons';
                                            }
                                        }
                                        else{
                                            unit_temp = 'fl oz';
                                        }
                                        $(e.target).parents('.cheddar_clp').siblings('.shoppinglist-items').find('span.unit_format').text(unit_temp);
                                    } else {
                                    }
                                    totalTemp = (parseFloat(temp) + parseFloat(totalTemp)).toFixed(2);
                                });
                                if (recipeIngredientsServings.find('span.measure-value').attr('data-type') == '2') {
                                    if ($(e.target).parents('.cheddar_clp').siblings('.shoppinglist-items').find('.extra_ingredient_input_number').attr('data-default') != 0) {
                                        let new_extra_ing_data = $(e.target).parents('.cheddar_clp').siblings('.shoppinglist-items').find('.extra_ingredient_input_number').attr('data_newest_default');
                                        totalTemp = parseFloat(new_extra_ing_data) + parseFloat(totalTemp);
                                    }
                                    if (recipeIngredientsServings.find('span.measure-value').attr("data-unit") == "metric") {
                                        if (totalTemp >= 1000) {
                                            $(e.target).parents('.cheddar_clp').siblings('.shoppinglist-items').find('.extra_ingredient_input_number').attr('data-new_gmweight_liquid_result', totalTemp);
                                            totalTemp = parseFloat(totalTemp / 1000).toFixed(2);
                                            $(e.target).parents('.cheddar_clp').siblings('.shoppinglist-items').find('span.unit_format').text('l');
                                            $(e.target).parents('.cheddar_clp').siblings('.shoppinglist-items').find('.extra_ingredient_input_number').attr('value', totalTemp);
                                        } else {
                                            $(e.target).parents('.cheddar_clp').siblings('.shoppinglist-items').find('span.unit_format').text('ml');
                                        }
                                    }
                                    $(this).parents('.shopping-name-list').find('.extra_ingredient_input_number').val(totalTemp);
                                } else {

                                }
                                if(sn_that.find('span.measure-value').attr('data-unit_format') == "E1" || sn_that.find('span.measure-value').attr('data-unit_format') == "E2" || sn_that.find('span.measure-value').attr('data-unit_format') == "E3" ){
                                    var serving_quantity = $(e.target).closest('.sl_amount_update').find('span.measure-value').attr('data-quantity');
                                    sn_that.find('span.measure-value').attr('data-quantity',serving_quantity);
                                }
                            }
                        }
                    });

                    //for extra ingredient update
                    let extra_ingredient_input_number = $('.recipeIngredientsServings').closest('.sl-name').find('.extra_ingredient_input_number');
                    $(extra_ingredient_input_number).on('input', searchDelay(function (e) {
                        let extra_ingredient_amount = $(this).val();
                        let extra_ing_unit = $(e.target).parents('.shopping-name-list').find('span.unit_format');
                        let extra_ingredient_unit = $(e.target).parents('.shopping-name-list').find('span.unit_format').text();
                        let unitconversion = {};
                        if ($(e.target).parents('.shopping-name-list').find('.recipeIngredientsServings').find('span.measure-value').attr('data-type') != '2') {
                            unitconversion = unitConversion(extra_ingredient_amount, extra_ingredient_unit, this, extra_ing_unit.text());
                        } else {
                            var totalTemp = 0;
                            $(this).parents('.shopping-name-list').find('.ingredient-item-body span.measure-value').each(function () {
                                calc_obj_temp = JSON.parse($(this).attr('data-amount_value'));
                                var temp = 0;
                                if ($(this).closest('.shopping-name-list').find('.recipeIngredientsServings').find('span.measure-value').attr('data-type') == '2') {
                                    temp = calc_for_liquid();
                                    unit_temp = '';
                                    if(temp >= 16 && temp <= 31.9){
                                        temp = temp * 0.0625;
                                        unit_temp = 'pints';
                                    }
                                    else if(temp >= 32 && temp <= 127.9){
                                        temp = temp * 0.03125;
                                        unit_temp = 'quarts';
                                    }
                                    else if (temp >= 128){
                                        temp = temp * 0.0078125;
                                        if(temp == 1){
                                            unit_temp = 'gallon';
                                        }
                                        else{
                                            unit_temp = 'gallons';
                                        }
                                    }
                                    else{
                                        unit_temp = 'fl oz';
                                    }
                                    $(e.target).parents('.shopping-name-list').find('span.unit_format').text(unit_temp);
                                } else {

                                }
                                totalTemp = parseFloat(temp) + parseFloat(totalTemp);
                            });
                            if ($(e.target).parents('.shopping-name-list').find('.recipeIngredientsServings').find('span.measure-value').attr('data-unit') == 'imperial') {
                                let new_liquid_extra_ing = parseFloat(extra_ingredient_amount - totalTemp);
                                unitconversion.extra_ing = new_liquid_extra_ing;
                                unitconversion.unit = 'fl oz';
                            } else {
                                let new_liquid_extra_ing = parseFloat(extra_ingredient_amount - totalTemp);
                                unitconversion.extra_ing = new_liquid_extra_ing;
                                //can be changeable
                                unitconversion.unit = extra_ingredient_unit;
                                if (extra_ingredient_unit == 'l') {
                                    let new_liquid_extra_ing = parseFloat((extra_ingredient_amount * 1000) - totalTemp);
                                    unitconversion.extra_ing = new_liquid_extra_ing;
                                    unitconversion.unit = 'l';
                                }
                            }

                        }
                        let extra_ingredient_gmweight = $(e.target).parents('.shopping-name-list').find('.recipeIngredientsServings').find('span.measure-value').attr('data-default_gmweight');
                        let default_initial_value = $(e.target).parents('.shopping-name-list').find('.recipeIngredientsServings').find('span.measure-value').attr('data-default_initial_value');
                        console.log("extra_ingredient_amount,extra_ingredient_gmweight,default_initial_value", extra_ingredient_amount, extra_ingredient_gmweight, default_initial_value);
                        let total_gm_weight = '';
                        if ($(e.target).parents('.shopping-name-list').find('.recipeIngredientsServings').find('span.measure-value').attr('data-unit') == 'imperial' && $(e.target).parents('.shopping-name-list').find('.recipeIngredientsServings').find('span.measure-value').attr('data-type') != '2') {
                            let temp_total = extra_ingredient_amount * extra_ingredient_gmweight;
                            total_gm_weight = temp_total / default_initial_value;
                            if ($(e.target).parents('.unit-select').find('span.unit_format').text() == 'lb') {
                                let temp_total = parseFloat(extra_ingredient_amount * extra_ingredient_gmweight).toFixed(2);
                                total_gm_weight = parseFloat(temp_total / default_initial_value).toFixed(2);
                            }
                        } else {
                            if (unitconversion.unit == 'kg' || unitconversion.unit == 'l') {
                                total_gm_weight = parseFloat(extra_ingredient_amount * 1000).toFixed(2);
                            } else {
                                total_gm_weight = extra_ingredient_amount;
                            }
                        }

                        let shoppingName = $(e.target).parents('.shopping-name-list').attr('data-shoppingname');
                        $.ajax({
                            url: "{{ route('change-shoppinglist-servings') }}",
                            type: 'post',
                            dataType: "json",
                            data: {
                                _token: "{{ csrf_token() }}",

                                extra_ingredient_input: unitconversion.extra_ing,
                                extra_ingredient_unit: unitconversion.unit,
                                extra_ingredient_gmweight: total_gm_weight,
                                shoppingname: shoppingName,
                            },
                            success: function (data) {
                                if (data.meta.message == 'success') {
                                    console.log("data.data", data.data);
                                    // $('.recipeIngredientsServings').find('span.measure-value').attr('data-gmweight',data.meta.gm_weight);
                                    $(e.target).parents('.shopping-name-list').find('.serving_btn_new').attr('disabled', 'disabled');
                                    $(e.target).closest('.sl-name').find('.extra_ingredient_input_number').css('color', '#DC3545');
                                    if ($(e.target).closest('.unit-select').find('.refresh_extra_ing').length < 1) {
                                        $(e.target).closest('.sl-name').find('.unit_format').parents('.unit-select').append("<img src={{asset('assets/images/shopping_list/Refresh.svg')}} class='refresh_extra_ing img-fluid  refresh-btn cursor-pointer' data-extra_ing='' >");
                                    }
                                    console.log("data.data", data.data);
                                    $(e.target).closest('.sl-name').find('.extra_ingredient_input_number').attr('data-new_original', data.data.extra_ing_value);
                                    let data_new_serving = $(e.target).attr('data-new_serving');
                                    console.log("e.target", $(e.target).closest('.sl-name').find('.extra_ingredient_input_number'));
                                    $(e.target).closest('.sl-name').find('.extra_ingredient_input_number').attr('data-old_serving', data_new_serving);
                                    $(e.target).closest('.sl-name').find('.extra_ingredient_input_number').attr('value', data.data.extra_ing_value);
                                    $(e.target).closest('.sl-name').find('.extra_ingredient_input_number').val(data.data.extra_ing_value);

                                    if ($(e.target).parents('.shopping-name-list').find('.recipeIngredientsServings').find('span.measure-value').attr('data-type') == '2') {
                                        let new_sum = (parseFloat(data.data.extra_ing_value) + parseFloat(totalTemp)).toFixed(2);
                                        if ($(e.target).parents('.shopping-name-list').find('.recipeIngredientsServings').find('span.measure-value').attr('data-unit') == 'metric') {
                                            if (new_sum >= 1000) {
                                                $(e.target).closest('.sl-name').find('.extra_ingredient_input_number').attr('data-new_gmweight_liquid_result', new_sum);
                                                new_sum = parseFloat(new_sum / 1000).toFixed(2);
                                            }
                                        }
                                        $(e.target).closest('.sl-name').find('.extra_ingredient_input_number').attr('data-default', new_sum);
                                        $(e.target).closest('.sl-name').find('.extra_ingredient_input_number').attr('value', new_sum);
                                        $(e.target).closest('.sl-name').find('.extra_ingredient_input_number').val(new_sum);
                                        $(e.target).closest('.sl-name').find('.extra_ingredient_input_number').attr('data_newest_default', data.data.extra_ing_value);
                                    } else {
                                        $(e.target).closest('.sl-name').find('.extra_ingredient_input_number').attr('data-default', data.data.extra_ing_value);
                                    }
                                    $(e.target).closest('.sl-name').find('.refresh_extra_ing').attr('data-extra_ing', data.data.extra_ing_id);
                                    if ($(e.target).parents('.shopping-name-list').find('.recipeIngredientsServings').find('span.measure-value').attr('data-type') == '1' && $(e.target).closest('.sl-name').find('.extra_ingredient_input_number').attr('data-default') != 0) {
                                        $(e.target).parents('.shopping-name-list').find('.recipeIngredientsServings').find('span.measure-value').attr('data-gmweight', data.data.extra_ing_gm_weight);
                                    }
                                    $(e.target).closest('.sl-name').find('.extra_ingredient_input_number').attr('data-refresh_updated_value', data.data.extra_ing_value);
                                    SuccessNotify('Extra Ingredients added successfully');
                                } else {
                                    ErrorNotify('Error While adding Extra Ingredients Servings');
                                }
                            }
                        });
                    }, 700));
                });


                //validation for serving-box issue element
                function isNumber(evt) {
                    var iKeyCode = (evt.which) ? evt.which : evt.keyCode;
                    if (iKeyCode == 46 && iKeyCode > 31 && (iKeyCode < 48 || iKeyCode > 57)) {
                        return false;
                    }
                    return true;
                }


                //new refresh code
                $(document).on('click', '.refresh_extra_ing', function () {
                    let that = $(this);
                    let extra_ing_id = $(this).attr('data-extra_ing');
                    let sn_that = $(this).parents('.shopping-name-list').find('.recipeIngredientsServings');
                    let extra_tag = $(this).parents('.shopping-name-list').find('.extra_ingredient_input_number');
                    let ing_item_body = $(this).parents('.shopping-name-list').find('.ingredient-item-body span.measure-value');
                    let sl_measure_value = $(this).parents('.shopping-name-list').find('span.measure-value');
                    $.ajax({
                        url: "{{ route('refresh-extra-ingredient') }}",
                        type: 'post',
                        dataType: "json",
                        data: {
                            _token: "{{ csrf_token() }}",
                            uuid: extra_ing_id
                        },
                        success: function (data) {
                            if (data.meta.status == 'ok') {
                                SuccessNotify('Extra Ingredients refreshed successfully');
                                var totalTemp = 0;
                                ing_item_body.each(function () {
                                    calc_obj_temp = JSON.parse($(this).attr('data-amount_value'));
                                    var temp = 0;
                                    if ($(this).closest('.shopping-name-list').find('.recipeIngredientsServings').find('span.measure-value').attr('data-type') == '2') {
                                        temp = calc_for_liquid();
                                        unit_temp = '';
                                        if(temp >= 16 && temp <= 31.9){
                                            temp = temp * 0.0625;
                                            temp = temp.toFixed(2);
                                            unit_temp = 'pints';
                                        }
                                        else if(temp >= 32 && temp <= 127.9){
                                            temp = temp * 0.03125;
                                            temp = temp.toFixed(2);
                                            unit_temp = 'quarts';
                                        }
                                        else if (temp >= 128){
                                            temp = temp * 0.0078125;
                                            temp = temp.toFixed(2);
                                            if(temp == 1){
                                                unit_temp = 'gallon';
                                            }
                                            else{
                                                unit_temp = 'gallons';
                                            }
                                        }
                                        else{
                                            unit_temp = 'fl oz';
                                        }
                                        extra_tag.parents('.shopping-name-list').find('span.unit_format').text(unit_temp);
                                    } else {

                                    }
                                    totalTemp = parseFloat(temp) + parseFloat(totalTemp);

                                });
                                if (sl_measure_value.attr('data-type') == '2') {
                                    extra_tag.attr('data-default', 0);
                                    if (sn_that.find('span.measure-value').attr("data-unit") == "metric") {
                                        if (totalTemp >= 1000) {
                                            extra_tag.attr('data-new_gmweight_liquid_result', totalTemp);
                                            new_totalTemp = parseFloat(totalTemp / 1000).toFixed(2);
                                            console.log("alert alert" + extra_tag);
                                            extra_tag.parents('.shopping-name-list').find('span.unit_format').text('l');
                                            totalTemp = new_totalTemp;
                                            extra_tag.attr('value', totalTemp);
                                        } else {
                                            extra_tag.parents('.shopping-name-list').find('span.unit_format').text('ml');
                                        }
                                    }
                                    extra_tag.val(totalTemp);
                                } else {
                                    extra_tag.attr('data-default', 0);
                                    let default_gmweight = sn_that.find('span.measure-value').attr('data-default_refresh_gmweight');
                                    sn_that.find('span.measure-value').attr('data-gmweight', default_gmweight);
                                    console.log("find sn_that", sn_that.find('span.measure-value').attr('data-gmweight'));
                                    calculation_new($("input[name='conversion']:checked").val(), sn_that);
                                }
                            } else {
                                ErrorNotify('Error While refreshing Extra Ingredients Servings');
                            }
                        }
                    });
                    $(this).remove();
                    extra_tag.css('color', 'black');
                    extra_tag.parents('.shopping-name-list').find('.serving_btn_new').removeAttr('disabled');
                });


                //delay on keyup and keypress
                function searchDelay(callback, ms) {
                    var timer = 0;
                    return function () {
                        var context = this, args = arguments;
                        clearTimeout(timer);
                        timer = setTimeout(function () {
                            callback.apply(context, args);
                        }, ms || 0);
                    };
                }

                //to update servings on add_serving,sub_serving,paste keyup
                function change_serving_update_new(sn_that, recipe_usersl_id, recipe_serving, recipeIngredientsServings, recipe_extra_ing_amount, recipe_extra_ing_unit, flag) {
                    recipe_e_i_amount = recipe_extra_ing_amount.attr('data-default');
                    recipe_e_i_unit = recipe_extra_ing_unit.text();
                    let extra_ingredient_gmweight = recipe_extra_ing_amount.parents('.shopping-name-list').find('.recipeIngredientsServings').find('span.measure-value').attr('data-default_gmweight');
                    let total_gm_weight = '';
                    if (recipe_extra_ing_amount.parents('.shopping-name-list').find('.recipeIngredientsServings').find('span.measure-value').attr('data-unit') == 'imperial') {
                        let temp_total = recipe_e_i_amount * extra_ingredient_gmweight;
                        console.log("recipe_e_i_amount", recipe_e_i_amount, extra_ingredient_gmweight);
                        console.log("add-serving temp_total", temp_total);
                        total_gm_weight = temp_total / default_initial_value_add_serving;
                        console.log("default_initial_value_add_serving", default_initial_value_add_serving);
                        console.log("add-serving total_gm_weight", total_gm_weight);
                    }
                    if (recipe_e_i_amount == 0) {
                        new_extra_ing = {}
                    } else {
                        new_extra_ing = {
                            extra_ingredient_input: recipe_e_i_amount,
                            extra_ingredient_unit: recipe_e_i_unit,
                            extra_ingredient_gmweight: total_gm_weight,
                        }
                    }
                    let ajax_dataVars = {};
                    if (flag == 1) {
                        ajax_dataVars = {
                            _token: "{{ csrf_token() }}",
                            new_extra_ing,
                            recipenamechangeservings: recipe_serving,
                            shoppingname: recipe_usersl_id,
                        }
                    } else {
                        ajax_dataVars = {
                            _token: "{{ csrf_token() }}",
                            new_extra_ing,
                            ingnamechangeservings: recipe_serving,
                            shoppingname: recipe_usersl_id,
                        }
                    }
                    $.ajax({
                        url: "{{ route('change-shoppinglist-servings') }}",
                        type: 'post',
                        dataType: "json",
                        data: ajax_dataVars,
                        success: function (data) {
                            if (data.meta.message == 'success') {
                                console.log("data data data", data);
                                recipeIngredientsServings.find('span.measure-value').attr('data-default_refresh_gmweight', data.meta.gm_weight);
                                if (recipe_extra_ing_amount.attr('data-default') == 0) {
                                    recipeIngredientsServings.find('span.measure-value').attr('data-gmweight', data.meta.gm_weight);
                                } else {
                                    recipeIngredientsServings.find('span.measure-value').attr('data-gmweight', data.data.extra_ing_gmweight);
                                }
                                if (recipeIngredientsServings.find('span.measure-value').attr('data-type') == '1') {
                                    calculation_new($("input[name='conversion']:checked").val(), sn_that);
                                }
                                SuccessNotify('Servings updated successfully');
                            } else {
                                ErrorNotify('Error While updating Servings');
                            }
                        }
                    });
                }
            </script>
            <script>
                let shareSLFormRules = {
                    email : {
                        required : true,
                        email: true
                    },
                    subject : {
                        required : true,
                    },
                }
                validateForm('#share_sl_form',shareSLFormRules)
                if($(".shopping_list_container").find('.shoppinglist-items').length == 0){
                    $(".share_sl").attr('disabled','disabled');
                    $(".mobile_share_btn").attr('disabled','disabled');
                }else{
                    $(".share_sl").removeAttr('disabled');
                    $(".mobile_share_btn").removeAttr('disabled');
                }
                function validateForm(formId, formRules, msg) {
                    $(formId).validate({
                        rules: formRules,
                        messages: msg,
                        ignore: ".hide",
                        errorPlacement: function (error, element) {
                            if (element.parents('div').hasClass("checker") || element.parents('div').hasClass("choice")
                                || element.parent().hasClass('bootstrap-switch-container')) {
                                if (element.parents('label').hasClass('checkbox-inline') || element.parents('label').hasClass('radio-inline')) {
                                    error.appendTo(element.parent().parent().parent());
                                }
                                else {
                                    error.appendTo(element.parent().parent().parent().parent().parent());
                                }
                            }
                            else {
                                error.insertAfter(element);
                            }
                        },
                        validClass: "validation-valid-label",
                        errorClass: 'validation-error-label',
                        successClass: 'validation-valid-label',
                        highlight: function (element, errorClass) {
                            $(element).removeClass(errorClass);
                        },
                        unhighlight: function (element, errorClass) {
                            $(element).removeClass(errorClass);
                        },
                        focusInvalid: false,
                        invalidHandler: function() {
                            $(this).find(":input.error:first").focus();
                        }
                    });
                }
                function shareShoppingList()
                {
                    let ShoppingListIngredient =[];
                    $('.shopping_list_container').find('.shopping-list-body').find('.shoppping_items_id').each(function($key, $val) {
                        let place_in_store = $(this).closest('.shopping-list-body').parent('.place').find('.list-heading').text();
                        let ing_name = $(this).find('.shoppinglist-items').children().children('.sl-name').length === 0 ? $(this).find('.ingredient-item-text').find('.measure-value').text()  : $(this).find('.shoppinglist-items').find('.sl-name').find('.measure-value').text();
                        let measure_value = $(this).find('.shoppinglist-items').children().children('.sl-name').length === 0 ? '' : $(this).find('.shoppinglist-items').find('.sl-name').find('.unit-select').find('.extra_ing_amount').val();
                        let measure_unit = $(this).find('.shoppinglist-items').children().children('.sl-name').length === 0 ? '' : $(this).find('.shoppinglist-items').find('.sl-name').find('.unit-select').find('span.unit_format').first().text();
                        let notes = $(this).find('.shoppinglist-items').find('.sl-name').find('.sl-desktop-width-360').children('p').length > 0 ? $(this).find('.sl-desktop-width-360').find('.note-overflow').text() : '';
                        // console.log("measure deep",measure_unit);
                        ShoppingListIngredient.push(
                            {
                                place_in_store:  place_in_store.trim(),
                                name:ing_name.trim(),
                                measure_value: measure_value.trim(),
                                measure_unit: measure_unit.trim(),
                                notes: notes.trim(),
                            }
                        );
                    });
                    // ShoppingListIngredient.map((item) => {console.log(`Name: ${item.name}`)});
                    $("#share_sl_form").find('#ing_data').val(JSON.stringify(ShoppingListIngredient));
                    return ShoppingListIngredient;
                }
                document.querySelector('.shareBtn')
                    .addEventListener('click', event => {
                        let ShoppingListIngredient = shareShoppingList();
                        let  final_list = ShoppingListIngredient.map((item) => {
                            console.log("length",item.measure_unit.length);
                            let item_list = `${item.name}${item.measure_value ? "-" + item.measure_value : ''} ${item.measure_unit ?  item.measure_unit.slice(0,2) : ''}${item.notes ? "-" + item.notes  : ''} \n\n`;
                            return  item_list;
                        })
                        // console.log(`Shared Shopping List: \n\n`+ final_list.join('') + `Explore this Shopping List shared by your friend by clicking below link: `);
                        if (navigator.share) {
                            navigator.share({
                                title: 'Primally Nourished',
                                text: `Shared Shopping List: \n\n`+ final_list.join('') + `Explore this Shopping List shared by your friend by clicking below link: `,
                                url: '{{url('/u/shopping-list')}}'
                            }).then(() => {
                                //
                            }).catch(err => {
                                //
                            });
                        } else {
                            console.log("Your Browser does not supports this Api");
                            // alert("Your Browser not supports this Api");
                        }
                    })
            </script>

@endsection