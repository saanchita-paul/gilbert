<!DOCTYPE html>
<html>
<head>
<style>
table {
  font-family: arial, sans-serif;
  border-collapse: collapse;
  width: 100%;
}

td, th {
  border: 1px solid #dddddd;
  text-align: left;
  padding: 8px;
}

.title{
    text-align: center;
}

tr:nth-child(even) {
  background-color: #dddddd;
}
</style>
</head>
<body>

<h2 class="title" style="color: red;">Water Lead Submission Failed.</h2>
<h3 class="title">Below table includes lead details. </h3>

<table>
  <tr>
    <th>Title</th>
    <th>Value </th>
  </tr>
  @foreach ($lead_info as $key => $value)
  <tr>
    <td>{{ $key }}</td>
    <td> {{ $value }}</td>
  </tr>
  @endforeach
</table>

</body>
</html>