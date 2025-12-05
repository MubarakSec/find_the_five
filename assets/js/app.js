// Frontend-only helpers for the Find The Five UI
(function () {
  const STORAGE_KEY = 'ftf_achievements';
  const ACHIEVEMENTS = ['sqli', 'idor', 'xss', 'cookie', 'privesc'];

  const loadState = () => {
    try {
      const raw = localStorage.getItem(STORAGE_KEY);
      const parsed = raw ? JSON.parse(raw) : {};
      return ACHIEVEMENTS.reduce((acc, key) => {
        acc[key] = Boolean(parsed[key]);
        return acc;
      }, {});
    } catch (err) {
      console.warn('Could not read achievements', err);
      return ACHIEVEMENTS.reduce((acc, key) => ({ ...acc, [key]: false }), {});
    }
  };

  const saveState = (state) => {
    localStorage.setItem(STORAGE_KEY, JSON.stringify(state));
  };

  const showMessage = (text, type = 'success') => {
    const placeholder = document.getElementById('alertPlaceholder');
    if (placeholder) {
      placeholder.innerHTML = `<div class="alert alert-${type} mb-3">${text}</div>`;
      setTimeout(() => (placeholder.innerHTML = ''), 2800);
    } else {
      console.log(text);
    }
  };

  const rankForCount = (count) => {
    if (count === 0) return 'Casual User';
    if (count <= 2) return 'Curious Brain';
    if (count <= 4) return 'Nearly Hacker';
    return 'Certified Hacker 😈🔥';
  };

  const syncAchievements = () => {
    const state = loadState();
    const unlockedCount = ACHIEVEMENTS.filter((k) => state[k]).length;
    const percent = Math.round((unlockedCount / ACHIEVEMENTS.length) * 100);

    document.querySelectorAll('[data-achievement]').forEach((node) => {
      const key = node.dataset.achievement;
      if (!key) return;
      const unlocked = Boolean(state[key]);
      node.classList.toggle('unlocked', unlocked);
      node.classList.toggle('locked', !unlocked);
      const status = node.querySelector('[data-status]');
      if (status) {
        status.textContent = unlocked ? 'Unlocked' : 'Locked';
        status.className = unlocked
          ? 'badge bg-success-subtle text-success'
          : 'badge bg-light text-dark';
      }
    });

    const bar = document.getElementById('achievementProgressBar');
    if (bar) bar.style.width = `${percent}%`;

    const text = document.getElementById('achievementProgressText');
    if (text) text.textContent = `${unlockedCount}/5 achievements`;

    const rankLabel = document.getElementById('rankLabel');
    if (rankLabel) rankLabel.textContent = rankForCount(unlockedCount);

    const finalBar = document.getElementById('finalProgressBar');
    if (finalBar) finalBar.style.width = `${percent}%`;
  };

  const markAchievement = (key) => {
    const state = loadState();
    if (!ACHIEVEMENTS.includes(key)) return;
    if (!state[key]) {
      state[key] = true;
      saveState(state);
      syncAchievements();
    }
  };

  const revealFlag = (selector) => {
    const flag = document.querySelector(selector);
    if (flag) flag.classList.remove('hidden-flag');
  };

  // SQLi lab
  const initSqliLab = () => {
    const form = document.getElementById('sqliForm');
    if (!form) return;
    const input = document.getElementById('sqliInput');
    const resultBox = document.getElementById('sqliResult');
    form.addEventListener('submit', (e) => {
      e.preventDefault();
      const payload = (input.value || '').toLowerCase();
      const looksInjected =
        payload.includes("' or") ||
        payload.includes('" or') ||
        payload.includes('1=1') ||
        payload.includes('--') ||
        payload.includes(' or 1');
      if (looksInjected) {
        resultBox.innerHTML =
          "<div class='text-success fw-semibold'>Welcome back, admin!</div><div class='small muted'>You bypassed the WHERE clause.</div>";
        revealFlag('#sqliFlag');
        showMessage('Injection worked — flag revealed.', 'success');
      } else {
        resultBox.innerHTML =
          "<div class='text-danger fw-semibold'>0 rows returned.</div><div class='small muted'>Try a boolean-based payload.</div>";
      }
    });
  };

  // IDOR lab
  const initIdorLab = () => {
    const recordBox = document.getElementById('idorRecord');
    if (!recordBox) return;
    const idLabel = document.getElementById('idorCurrentId');
    const id = new URLSearchParams(window.location.search).get('id') || '1';
    if (idLabel) idLabel.textContent = id;

    if (id !== '1') {
      recordBox.innerHTML = `
        <div class="fw-semibold">Admin profile (id: ${id})</div>
        <div class="muted small">Achievements: 5/5, Email: root@example.com</div>
        <div class="mini-flag mt-2">Unauthorized data exposure!</div>
      `;
      revealFlag('#idorFlag');
      showMessage('You accessed another profile — IDOR achieved.', 'success');
    } else {
      recordBox.innerHTML = `
        <div class="fw-semibold">Your profile (id: 1)</div>
        <div class="muted small">Achievements: 0/5, Email: you@example.com</div>
        <div class="mt-2">Try changing <code>?id=2</code>.</div>
      `;
    }
  };

  // XSS lab
  const initXssLab = () => {
    const form = document.getElementById('bioForm');
    if (!form) return;
    const input = document.getElementById('bioInput');
    const preview = document.getElementById('bioPreview');
    form.addEventListener('submit', (e) => {
      e.preventDefault();
      const value = input.value || '';
      preview.innerHTML = value;
      if (/<script/i.test(value)) {
        revealFlag('#xssFlag');
        showMessage('Script executed in profile preview — stored XSS achieved.', 'success');
      } else {
        showMessage('Saved (UI only). Try injecting a script tag to exploit.', 'info');
      }
    });
  };

  // Cookie lab
  const initCookieLab = () => {
    const cookieBox = document.getElementById('cookieBox');
    if (!cookieBox) return;
    const ensureCookie = () => {
      const hasCookie = document.cookie.includes('access_level');
      if (!hasCookie) document.cookie = 'access_level=learner; path=/';
    };
    ensureCookie();
    const refresh = () => {
      cookieBox.value = document.cookie || 'No cookies found.';
      if (
        document.cookie.includes('access_level=elite') ||
        document.cookie.includes('access_level=admin')
      ) {
        revealFlag('#cookieFlag');
        showMessage('Cookie elevated — flag unlocked.', 'success');
      }
    };
    refresh();
    const btn = document.getElementById('refreshCookies');
    if (btn) btn.addEventListener('click', refresh);
  };

  // Privilege escalation lab
  const initPrivescLab = () => {
    const roleSelect = document.getElementById('roleSelect');
    const payloadArea = document.getElementById('rolePayload');
    const resultBox = document.getElementById('roleResult');
    const sendBtn = document.getElementById('sendRoleRequest');
    if (!roleSelect || !payloadArea || !sendBtn || !resultBox) return;

    roleSelect.addEventListener('change', () => {
      const payload = { id: 1, role: roleSelect.value, note: 'promotion-request' };
      payloadArea.value = JSON.stringify(payload, null, 2);
    });

    sendBtn.addEventListener('click', () => {
      let role = roleSelect.value;
      try {
        const parsed = JSON.parse(payloadArea.value);
        if (parsed.role) role = parsed.role;
      } catch (err) {
        // keep role from select if JSON fails
      }
      resultBox.innerHTML = `<div class="fw-semibold">Requested role: ${role}</div>`;
      if (String(role).toLowerCase() === 'admin') {
        resultBox.innerHTML += `<div class="mini-flag mt-2">Server accepted elevated role.</div>`;
        revealFlag('#privescFlag');
        showMessage('Privilege escalation simulated. Flag unlocked.', 'success');
      } else {
        resultBox.innerHTML += `<div class="muted small">No change. Try setting role to "admin".</div>`;
      }
    });
  };

  // Final flag page
  const initFinalFlag = () => {
    const finalFlag = document.getElementById('finalFlag');
    if (!finalFlag) return;
    const form = document.getElementById('finalFlagForm');
    const submitBtn = document.getElementById('finalFlagSubmit');
    const secret = finalFlag.dataset.secret || '';

    const checkCompletion = () => {
      const state = loadState();
      const unlockedCount = ACHIEVEMENTS.filter((k) => state[k]).length;
      if (unlockedCount === ACHIEVEMENTS.length) {
        revealFlag('#finalFlag');
        showMessage('All achievements done. Final flag revealed.', 'success');
      }
    };

    if (form) {
      form.addEventListener('submit', (e) => {
        e.preventDefault();
        const code = (document.getElementById('finalCodeInput').value || '').trim();
        if (code.toUpperCase() === secret.toUpperCase()) {
          revealFlag('#finalFlag');
          showMessage('Correct master code. Final flag unlocked.', 'success');
        } else {
          showMessage('Incorrect code. Inspect the client for clues.', 'danger');
        }
      });
    }

    if (submitBtn) {
      submitBtn.addEventListener('click', () => {
        if (finalFlag.classList.contains('hidden-flag')) {
          showMessage('Unlock the flag first, then submit.', 'warning');
        } else {
          showMessage('Final flag submitted (frontend only).', 'success');
        }
      });
    }

    checkCompletion();
  };

  // Flag submit buttons
  const initFlagSubmitButtons = () => {
    document.querySelectorAll('.flag-submit').forEach((btn) => {
      btn.addEventListener('click', () => {
        const targetSelector = btn.dataset.flagTarget;
        const achievement = btn.dataset.achievement;
        const target = targetSelector ? document.querySelector(targetSelector) : null;
        if (target && target.classList.contains('hidden-flag')) {
          showMessage('Trigger the vulnerability to reveal the flag first.', 'warning');
          return;
        }
        if (achievement) {
          markAchievement(achievement);
        }
        showMessage('Flag submitted. Backend validation is pending.', 'success');
      });
    });
  };

  // Profile helper
  const initProfilePage = () => {
    const label = document.getElementById('profileIdLabel');
    if (!label) return;
    const id = new URLSearchParams(window.location.search).get('id') || '1';
    label.textContent = `Profile ID: ${id}`;
  };

  document.addEventListener('DOMContentLoaded', () => {
    syncAchievements();
    initFlagSubmitButtons();
    initSqliLab();
    initIdorLab();
    initXssLab();
    initCookieLab();
    initPrivescLab();
    initFinalFlag();
    initProfilePage();
  });
})();
