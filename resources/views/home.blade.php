@extends('layouts.app')

@section('content')
    <App></App>
@endsection

<script>
    import App from "../js/components/App"
    export default {
        components: {App}
    }
</script>
