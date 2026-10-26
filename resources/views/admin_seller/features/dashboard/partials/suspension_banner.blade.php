{{-- Banner Notifikasi Suspensi & Formulir Banding --}}
@if(Auth::user()->isSuspended())
    <div class="suspension-alert-card" style="background: #ffffff; border: 2px solid #fecaca; border-radius: 16px; padding: 24px; margin-bottom: 24px; box-shadow: 0 4px 20px rgba(220, 38, 38, 0.08);">
        <div style="display: flex; align-items: flex-start; gap: 16px; margin-bottom: 18px;">
            <div style="width: 48px; height: 48px; border-radius: 14px; background: #fee2e2; color: #dc2626; display: flex; align-items: center; justify-content: center; font-size: 22px; flex-shrink: 0;">
                <i class="fas fa-exclamation-triangle"></i>
            </div>
            <div>
                <h2 style="font-size: 18px; font-weight: 800; color: #991b1b; margin-bottom: 4px;">
                    {{ __('dashboard.suspended_title') }}
                </h2>
                <p style="font-size: 13px; color: #64748b; line-height: 1.5;">
                    {{ __('dashboard.suspended_desc') }}
                </p>
            </div>
        </div>

        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 14px; background: #fef2f2; border: 1px solid #fecaca; border-radius: 12px; padding: 14px 18px; margin-bottom: 20px; font-size: 13px;">
            <div>
                <strong style="color: #991b1b; display: block; margin-bottom: 2px;"><i class="fas fa-info-circle"></i> {{ __('dashboard.suspend_reason') }}</strong>
                <span style="color: #475569;">{{ Auth::user()->suspend_reason ?? __('dashboard.suspend_default_reason') }}</span>
            </div>
            <div>
                <strong style="color: #991b1b; display: block; margin-bottom: 2px;"><i class="fas fa-clock"></i> {{ __('dashboard.suspend_until') }}</strong>
                <span style="color: #475569; font-weight: 700;">
                    @if(Auth::user()->suspended_until)
                        {{ __('dashboard.suspend_until_prefix') }} {{ Auth::user()->suspended_until->format('d M Y, H:i') }} ({{ Auth::user()->suspended_until->diffForHumans() }})
                    @else
                        {{ __('dashboard.suspend_permanent') }}
                    @endif
                </span>
            </div>
        </div>

        <!-- Appeal Section -->
        @if(isset($activeAppeal) && $activeAppeal->status === 'pending')
            <div style="background: #f0fdf4; border: 1.5px solid #bbf7d0; border-radius: 12px; padding: 16px 20px; display: flex; align-items: center; gap: 14px;">
                <i class="fas fa-hourglass-half" style="font-size: 24px; color: #16a34a;"></i>
                <div style="flex: 1;">
                    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 2px;">
                        <div style="font-weight: 800; color: #166534; font-size: 14px;">{{ __('dashboard.appeal_pending_title') }}</div>
                        <span style="font-size: 11px; font-weight: 800; background: #dcfce7; color: #166534; padding: 2px 8px; border-radius: 6px;">{{ __('dashboard.appeal_count_prefix') }}{{ $totalAppealsCount ?? 1 }}/{{ $maxAppeals ?? 3 }}</span>
                    </div>
                    <div style="font-size: 13px; color: #15803d; line-height: 1.4;">
                        {{ __('dashboard.appeal_submitted_on') }}<strong>{{ $activeAppeal->created_at->format('d M Y, H:i') }}</strong> {{ __('dashboard.appeal_in_progress') }}
                    </div>
                </div>
            </div>
        @elseif(isset($totalAppealsCount) && $totalAppealsCount >= ($maxAppeals ?? 3) && isset($activeAppeal) && $activeAppeal->status === 'rejected')
            <div style="background: #fff1f2; border: 1.5px solid #fecdd3; border-radius: 12px; padding: 18px 20px;">
                <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 6px;">
                    <div style="font-weight: 800; color: #be123c; font-size: 14px; display: flex; align-items: center; gap: 8px;">
                        <i class="fas fa-ban"></i> {{ __('dashboard.appeal_limit_reached') }}
                    </div>
                    <span style="font-size: 11px; font-weight: 800; background: #fee2e2; color: #991b1b; padding: 2px 8px; border-radius: 6px;">{{ __('dashboard.appeal_3_of_3') }}</span>
                </div>
                <div style="font-size: 13px; color: #4c0519; line-height: 1.5; margin-bottom: 10px;">
                    <strong>{{ __('dashboard.admin_last_note') }}</strong> {{ $activeAppeal->admin_notes ?? __('dashboard.appeal_reject_default') }}
                </div>
                <div style="font-size: 12px; color: #64748b; background: #ffffff; padding: 10px 14px; border-radius: 8px; border: 1px solid #f1f5f9; line-height: 1.5;">
                    <i class="fas fa-info-circle" style="color: #64748b;"></i> {{ __('dashboard.appeal_max_reached_desc_1') }}<a href="{{ route('contact.form') }}" style="color: #5A5BF1; font-weight: 700; text-decoration: underline;">{{ __('dashboard.contact_us') }}</a>.
                </div>
            </div>
        @elseif(isset($activeAppeal) && $activeAppeal->status === 'rejected')
            <div style="background: #fff1f2; border: 1.5px solid #fecdd3; border-radius: 12px; padding: 16px 20px;">
                <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 4px;">
                    <div style="font-weight: 800; color: #be123c; font-size: 14px; display: flex; align-items: center; gap: 8px;">
                        <i class="fas fa-times-circle"></i> {{ __('dashboard.appeal_previously_rejected') }}
                    </div>
                    <span style="font-size: 11px; font-weight: 800; background: #fee2e2; color: #991b1b; padding: 2px 8px; border-radius: 6px;">{{ __('dashboard.appeal_count_prefix') }}{{ $totalAppealsCount }}/{{ $maxAppeals ?? 3 }}</span>
                </div>
                <div style="font-size: 13px; color: #4c0519; line-height: 1.4; margin-bottom: 12px;">
                    <strong>{{ __('dashboard.admin_note') }}</strong> {{ $activeAppeal->admin_notes ?? __('dashboard.appeal_reject_default') }}
                </div>

                @if(isset($canSubmitAppeal) && !$canSubmitAppeal)
                    {{-- Cooldown 1 Hari Aktif --}}
                    <div style="background: #fefce8; border: 1.5px solid #fef08a; border-radius: 10px; padding: 12px 16px; margin-top: 10px;">
                        <div style="font-weight: 700; color: #854d0e; font-size: 13px; display: flex; align-items: center; gap: 8px; margin-bottom: 4px;">
                            <i class="fas fa-clock"></i> {{ __('dashboard.cooldown_title') }}
                        </div>
                        <div style="font-size: 12px; color: #a16207; line-height: 1.5;">
                            {{ __('dashboard.cooldown_desc_1') }}<strong>{{ $remainingCooldownText }}</strong> {{ __('dashboard.cooldown_desc_2') }}{{ $cooldownUntil ? $cooldownUntil->format('d M Y, H:i') : '-' }} WIB).
                        </div>
                        <div style="font-size: 11px; color: #a16207; margin-top: 6px;">
                            <i class="fas fa-shield-alt"></i> {{ __('dashboard.remaining_appeals_prefix') }}<strong>{{ $remainingAttempts ?? 1 }} kali</strong> lagi.
                        </div>
                    </div>
                @else
                    {{-- Form Banding Ulang --}}
                    <p style="font-size: 12px; color: #64748b; margin-bottom: 10px;">
                        {{ __('dashboard.you_have_remaining') }}<strong>{{ $remainingAttempts ?? 1 }}</strong> {{ __('dashboard.appeal_chances') }}
                    </p>
                    
                    <form action="{{ route('admin.appeal.store') }}" method="POST">
                        @csrf
                        <div style="margin-bottom: 12px;">
                            <textarea name="appeal_reason" rows="3" placeholder="{{ __('dashboard.appeal_placeholder_2') }}" style="width: 100%; padding: 12px 14px; border: 1.5px solid #e2e8f0; border-radius: 10px; font-size: 13px; font-family: inherit; outline: none; box-sizing: border-box;" required></textarea>
                        </div>
                        <button type="submit" style="background: #5A5BF1; color: #fff; border: none; border-radius: 10px; padding: 10px 20px; font-weight: 700; font-size: 13px; cursor: pointer; display: inline-flex; align-items: center; gap: 8px;">
                            <i class="fas fa-paper-plane"></i> {{ __('dashboard.submit_appeal_retry') }}{{ ($totalAppealsCount ?? 0) + 1 }}{{ __('dashboard.of_3') }}
                        </button>
                    </form>
                @endif
            </div>
        @else
            <!-- Initial Appeal Form -->
            <div style="border-top: 1px solid #f1f5f9; padding-top: 18px;">
                <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 6px;">
                    <h3 style="font-size: 15px; font-weight: 800; color: #1e293b; margin: 0;">
                        <i class="fas fa-file-signature" style="color: #5A5BF1;"></i> {{ __('dashboard.submit_appeal_title') }}
                    </h3>
                    <span style="font-size: 11px; font-weight: 800; background: #EEF0FE; color: #5A5BF1; padding: 2px 8px; border-radius: 6px;">{{ __('dashboard.chance_1_of_3') }}</span>
                </div>
                <p style="font-size: 12px; color: #64748b; margin-bottom: 12px;">
                    {{ __('dashboard.appeal_initial_desc') }}
                </p>
                <form action="{{ route('admin.appeal.store') }}" method="POST">
                    @csrf
                    <div style="margin-bottom: 12px;">
                        <textarea name="appeal_reason" rows="3" placeholder="{{ __('dashboard.appeal_placeholder_1') }}" style="width: 100%; padding: 12px 14px; border: 1.5px solid #cbd5e1; border-radius: 10px; font-size: 13px; font-family: inherit; outline: none; box-sizing: border-box;" required></textarea>
                    </div>
                    <button type="submit" style="background: #5A5BF1; color: #fff; border: none; border-radius: 10px; padding: 11px 22px; font-weight: 700; font-size: 13px; cursor: pointer; display: inline-flex; align-items: center; gap: 8px;">
                        <i class="fas fa-paper-plane"></i> {{ __('dashboard.submit_appeal_btn') }}
                    </button>
                </form>
            </div>
        @endif
    </div>
@endif
