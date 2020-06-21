@if(empty($user->getFirstMedia('avatar')))
    <img {{ $attributes }} src="{{ asset('svg/user-default.svg') }}">
@else
    {!! $user->getFirstMedia('avatar')->img('', $attributes->getIterator()->getArrayCopy()) !!}
@endif