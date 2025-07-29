<table>
  <thead>
    <tr>
      <th>{{ __('general.name') }}</th>
      <th>{{ __('general.email') }}</th>
      <th>{{ __('general.phone') }}</th>
      <th>{{ __('general.company') }}</th>
      <th>{{ __('general.gender') }}</th>
      <th>{{ __('general.id_no') }}</th>
    </tr>
  </thead>
  <tbody>
    @foreach($arr_user as $user)
      <tr>
        <td>{{ $user->name }}</td>
        <td>{{ $user->email }}</td>
        <td>{{ $user->phone }}</td>
        <td>{{ $user->company->name }}</td>
        <td>{{ __('general.'.($user->gender == 1 ? 'male' : 'female')) }}</td>
        <td>{{ $user->id_no }}</td>
      </tr>
    @endforeach
  </tbody>
</table>
