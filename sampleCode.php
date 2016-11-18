@extends('layouts.moneyman')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-8 col-md-offset-2">
            <div class="panel panel-default">                
                <div class="panel-body">
                    <h3>Expenses Records{{$extHead}}</h3>
                    <div class="row">
                        <div class="col-xs-12">
                            <div class="row" style="margin-bottom: 20px">
                                <div class="col-xs-8 col-sm-9 col-md-10 col-lg-9">
                                    <div class="btn-group" role="group" aria-label="...">
                                        <button type="button" class="btn btn-primary btn-sm"><span class="glyphicon glyphicon-plus"></span><span class="hidden-xs"> Add Expense</span></button>
                                        <button type="button" class="btn btn-primary btn-sm"><span class="glyphicon glyphicon-download-alt"></span><span class="hidden-xs"> Import Data</span></button>                                        
                                        
                                        <!-- show the 'quick summary' button only if there are records retrieved. Otherwise, there's no point in showing it -->
                                        @if($expenses->count()>0)
                                            <button type="button" class="btn btn-success btn-sm" data-toggle="modal" data-target="#summaryModal"><span class="glyphicon glyphicon-signal"></span><span class="hidden-xs"> Summary</span></button>                                        
                                        @endif
                                    </div>             
                                </div>                       

                                <div class="col-xs-4 col-sm-3 col-md-2 col-lg-3">
                                    <div class="btn-group pull-right" role="group" aria-label="...">
                                        <a role="button" href="/viewAllExpenses" class="btn btn-default btn-sm"><span class="glyphicon glyphicon-eye-open"></span><span class="hidden-xs hidden-sm hidden-md"> Show All</span></a>                                        
                                        <button type="button" class="btn btn-default btn-sm" data-toggle="modal" data-target="#filterModal">
                                            <span class="glyphicon glyphicon-filter"></span><span class="hidden-xs hidden-sm hidden-md"> Filter</span>
                                        </button>
                                    </div>                                
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- if there are no expenses records retrieved -->
                    @if($expenses->count()<=0)     
                        @php
                            $currencySymbol = '';
                        @endphp

                        <div class="alert alert-info" role="alert">No entries found.</div>
                    @endif
                </div>

                <div class="table-responsive">                    
                    @if($expenses->count()>0)
                        <table class="table table-striped table-bordered table-hover">
                            <thead class="thead-inverse">
                                <tr>
                                    <th>Date</th>
                                    <th>Expense type</th>
                                    <th>Amount</th>                                    
                                    <th class="hidden-xs hidden-sm">Details</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($expenses as $expense)
                                    <tr>                                    
                                        <td style="width: 14%">{{$expense->date}}</td>
                                        <td style="width: 18%">{{$expense->expense_type}}</td>
                                        
                                        @php                                            
                                            //Convert value to display currency setting
                                            $displayCurrency = $setting['display_currency'];                     

                                            $currencySymbol = expenseFunctions::currencySymbol($displayCurrency);
                                            $convertedExpenseAmount = expenseFunctions::convertAmount($displayCurrency, $expense, $exchangeRate);                                            
                                        @endphp

                                        <td width="18%">{{$currencySymbol.' '.number_format($convertedExpenseAmount, 2, '.', ',')}}</td>                                                    
                                        <td class="hidden-xs hidden-sm">{{$expense->details}}</td>
                                        <td style="width: 8%">
                                            <div class="btn-group btn-group-xs" role="group" aria-label="...">                                                
                                                <button type="button" class="btn btn-default"><span class="glyphicon glyphicon-edit"></span></button>
                                                <button type="button" class="btn btn-default"><span class="glyphicon glyphicon-trash"></span></button>                                                                
                                            </div>                                                            
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    @endif                                            
                </div>                           
            </div>
        </div>
    </div>
</div>


<!-- Summary Modal -->
<div class="modal fade" id="summaryModal" tabindex="-1" role="dialog" aria-labelledby="filterModalLabel">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="filterModalLabel">Summary</h4>            
            </div>

            <div class="modal-body"> 
            </div>

            <div class="table-responsive">
                <table class="table table-striped table-bordered table-hover">
                    <tbody>
                        @if($displayFilter=='show all')
                            <tr>
                                <td>You spend this much daily (average)</td>
                                <td>{{$currencySymbol.number_format($calculations['average daily expense'], 2, '.', ',')}}</td>
                            </tr>                        
                            
                            <tr>
                                <td>You spend this much monthly (average)</td>
                                <td>{{$currencySymbol.number_format($calculations['average monthly expense'], 2, '.', ',')}}</td>
                            </tr>                        

                            <tr>
                                <td>You spend this much yearly (average)</td>
                                <td>{{$currencySymbol.number_format($calculations['average annual expense'], 2, '.', ',')}}</td>
                            </tr>
                        @endif

                        @if($displayFilter=='month')
                            <tr>
                                <td>You spend this much daily (average)</td>
                                <td>{{$currencySymbol.number_format($calculations['average daily expense'], 2, '.', ',')}}</td>
                            </tr>                                                
                        @endif

                        @if($displayFilter=='year')
                            <tr>
                                <td>You spend this much daily (average)</td>
                                <td>{{$currencySymbol.number_format($calculations['average daily expense'], 2, '.', ',')}}</td>
                            </tr>                        
                            
                            <tr>
                                <td>You spend this much monthly (average)</td>
                                <td>{{$currencySymbol.number_format($calculations['average monthly expense'], 2, '.', ',')}}</td>
                            </tr>                                                
                        @endif
                    </tbody>
                </table>

                <div style="padding:15px"><h2>Total: {{$currencySymbol.number_format($calculations['overall total'], 2, '.', ',')}}</h2></div>
            
            </div>
        </div>
    </div>
</div>

<!-- Filter Modal -->
<div class="modal fade" id="filterModal" tabindex="-1" role="dialog" aria-labelledby="filterModalLabel">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="filterModalLabel">Filter Data View</h4>
            </div>

            {!! Form::open(['url' => 'viewAllExpenses/filter', 'method' => 'get']) !!}
            <div class="modal-body"> 
                <!-- Filter by date -->
                <div class="form-group">
                    {{Form::radio('filterOpt', 'date',true)}}  {{Form::label('filterTxt_date', 'Filter by date')}}
                    {{Form::date('dateValue', \Carbon\Carbon::now(), array_merge(['class' => 'form-control', 'id' => 'dateFilter_text']))}}                            
                </div>

                <!-- Filter by month -->
                <div class="form-group">
                    {{Form::radio('filterOpt', 'month')}}  {{Form::label('filterTxt_month', 'Filter by month (current year)')}}                    
                    {{Form::selectMonth('monthValue', NULL, array_merge(['class' => 'form-control', 'id' => 'monthFilter_text']))}}                
                </div>

                <!-- Filter by year -->
                <div class="form-group">
                    {{Form::radio('filterOpt', 'year')}}  {{Form::label('filterTxt_year', 'Filter by year (current year)')}}                    
                    {{Form::selectRange('yearValue', 2007, 2025, 2016, array_merge(['class' => 'form-control', 'id' => 'yearFilter_text']))}}                                
                </div>
            </div>   

            <div class="modal-footer">                
                {{ Form::submit('Refresh', array_merge(['class' => 'btn btn-primary', 'id' => 'submitBtn'])) }}
                <!--<button class="btn btn-primary" type="submit"><span class="glyphicon glyphicon-refresh"></span> Refresh</button>                
                <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>-->
            </div>            
            {!! Form::close() !!}
        </div>
    </div>
</div>
@endsection
