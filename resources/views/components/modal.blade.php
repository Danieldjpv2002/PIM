<form class="modal fade" id="{{ $id }}" tabindex="-1" style="display: none;" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered {{ $size ?? '' }}">
    <div class="modal-content ">
      <div class="modal-header">
        <h4 class="modal-title">{{ $title }}</h4>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        {{ $slot }}
      </div>
      @if (!isset($hideFooter) || !$hideFooter)
        <div class="modal-footer">
          <button class="btn btn-sm btn-danger pull-left" type="button"
            data-bs-dismiss="modal">{{ $btnCancelText ?? 'Cerrar' }}</button>
          <button class="btn btn-sm btn-success pull-right" type="submit">{{ $btnSubmitText ?? 'Aceptar' }}</button>
        </div>
      @endif
    </div>
  </div>
</form>
