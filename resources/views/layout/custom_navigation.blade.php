<div class="d-flex align-items-center justify-content-between">
  @if(empty($include_back_button) || (!empty($include_back_button) && $include_back_button))
    <div class="d-flex align-items-center">
      <button class="btn btn-primary" onclick="back_page()"><i class="fa-solid fa-arrow-left m-0"></i></button>
      <h5 class="mb-0 text-gray-800 font-weight-bold ml-3">{{ $title }}</h5>
    </div>
  @endif

  <div>
    @if(!empty($arr_additional_item))
      @foreach ($arr_additional_item as $item)
        @if($item["type"] == "to_other_page")
          <a href="{{ $item["url"] }}" class="btn btn-primary">{{ $item["text"] }}</a>
        @endif
      @endforeach
    @endif
  </div>
</div>