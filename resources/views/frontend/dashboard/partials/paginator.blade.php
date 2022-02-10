@if($paginators->hasPages())
      <div class=" pagination-wrapper desktop--view">
         <div class="btn-group">
            <a class="btn btn-primary {{$paginators->onFirstPage() ? 'disabled' : ''}}"
               href="{{ $paginators->appends($_GET)->url(1) }}">{{__('First')}}</a>
            <a class="btn btn-primary {{$paginators->onFirstPage() ? 'disabled' : ''}}"
               href="{{ $paginators->appends($_GET)->previousPageUrl() }}">{{__('Previous')}}</a>
            <a class="btn btn-primary {{!$paginators->hasMorePages() ? 'disabled' : ''}}"
               href="{{ $paginators->appends($_GET)->nextPageUrl() }}">{{__('Next')}}</a>
            <a class="btn btn-primary {{!$paginators->hasMorePages() ? 'disabled' : ''}}"
               href="{{ $paginators->appends($_GET)->url($paginators->lastPage()) }}">{{__('Last')}}</a>
         </div>
      </div>
      <div class=" pagination-wrapper mobile-view">
          <div class="btn-group">
            <a class="btn btn-primary {{$paginators->onFirstPage() ? 'disabled' : ''}}"
               href="{{ $paginators->appends($_GET)->url(1) }}">{{__('First')}}</a>
            <a class="btn fa fa-angle-double-left {{$paginators->onFirstPage() ? 'disabled' : ''}}"
               href="{{ $paginators->appends($_GET)->previousPageUrl() }}"></a>
            <a class="btn fa fa-angle-double-right {{!$paginators->hasMorePages() ? 'disabled' : ''}}"
               href="{{ $paginators->appends($_GET)->nextPageUrl() }}"></a>
            <a class="btn btn-primary {{!$paginators->hasMorePages() ? 'disabled' : ''}}"
               href="{{ $paginators->appends($_GET)->url($paginators->lastPage()) }}">{{__('Last')}}</a>
         </div>
    </div>
      
@endif
