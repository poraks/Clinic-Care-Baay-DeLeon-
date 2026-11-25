@extends('errors.layout')

@section('code', '403')
@section('title', 'Unauthorized Access')
@section('message', $exception->getMessage() ?: 'Access denied. Your account role does not have permission to view or modify this resource.')