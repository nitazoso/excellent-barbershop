<?php

namespace App\Http\Requests\Admin;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class CreateManualBookingRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'customer_name' => ['required', 'string', 'max:255'],
            'customer_phone' => ['required', 'string', 'max:20'],
            'membership_type' => ['required', 'string', 'in:regular,vip'],
            'service_id' => ['required', 'integer', 'exists:services,id'],
            'barber_id' => ['required', 'integer', 'exists:barbers,id'],
            'schedule_id' => ['required', 'integer', 'exists:schedules,id'],
            'notes' => ['nullable', 'string', 'max:2000'],
        ];
    }

    /**
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'customer_name.required' => 'Nama pelanggan wajib diisi.',
            'customer_phone.required' => 'Nomor HP pelanggan wajib diisi.',
            'membership_type.in' => 'Tipe member tidak valid.',
            'service_id.exists' => 'Layanan yang dipilih tidak ditemukan.',
            'barber_id.exists' => 'Barber yang dipilih tidak ditemukan.',
            'schedule_id.exists' => 'Jadwal yang dipilih tidak ditemukan.',
        ];
    }
}
