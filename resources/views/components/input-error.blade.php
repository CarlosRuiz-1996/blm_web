<style>
    .custom-alert {
    background-color: #ffcccc; /* rojo menos fuerte */
    border-color: #ff6666; /* rojo medio */
    color: #990000; /* rojo oscuro */
    padding: 1rem 1.5rem; /* ajustar según sea necesario */
    border-radius: 0.25rem; /* redondeado */
    position: relative;
}
</style>

@props(['for'])

@error($for)
    <p {{ $attributes->merge(['class' => 'alert alert-danger custom-alert']) }}>{{ $message }}</p>
@enderror
