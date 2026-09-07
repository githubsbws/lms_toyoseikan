<nav style="width: 100%; display: flex; flex-direction: row; justify-content: space-between; align-items: center; padding-inline: 20px; padding-block: 5px; margin-bottom: 20px; background-color: #fff;"
    class="custom-navbar">
    <a href="#" style=" font-size: larger;">
        <div style="display: flex; flex-direction: row; align-items: center; gap: 10px;">
            <div
                style="color: var(--primary-color); background-color: var(--primary-color-transparent); padding-block: 10px; padding-inline: 5px; border-radius: 100%;">
                <i class="fa-solid fa-user-group fa-xl"></i>
            </div>
            <div style="display: flex; flex-direction: column;">
                <strong>{{ $dashboardTitle ?? 'Dashboard' }}</strong>
                <span style="font-size: smaller;">ภาพรวมการเรียนรู้ {{ $dashboardSector ?? 'Line' }}</span>
            </div>
        </div>
    </a>
    <div style="display: flex; flex-direction: column; gap: 5px;">
        <div style="display: flex; flex-direction: row; justify-content: end; align-items: center; gap: 40px; width: 100%;">
            <div style="display: flex; flex-direction: row; align-items: center; gap: 15px;">
                <div style="display: flex; flex-direction: column; font-size: smaller;">
                    <strong>คุณ {{ auth()->user()->Profiles->firstname ?? '' }} {{ auth()->user()->Profiles->lastname ?? '' }}</strong>
                    <span>{{ auth()->user()->orgchart->title ?? '' }}</span>
                </div>
            </div>
            <span class="caret"></span>
        </div>
    </div>
</nav>
