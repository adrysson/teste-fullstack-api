@if(empty($user->getFirstMedia('avatar')))
    <img {{ $attributes }} src="{{ asset('svg/user-default.svg') }}" class="rounded-circle mr-2" width="40px">
@else
    <img src="{{ $user->getFirstMedia('avatar')->getUrl() }}" class="rounded-circle mr-2" width="40px">
@endif