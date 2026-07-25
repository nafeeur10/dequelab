<?php
/* ============================================================
 * Deque Lab — contact section markup
 * Requires inc/lib.php + inc/contact-process.php to have run.
 * Optional vars (set before include to customize copy):
 *   $contact_eyebrow, $contact_heading (trusted HTML),
 *   $contact_lede, $contact_default_interest
 * ============================================================ */

$contact_eyebrow ??= 'Stay in the loop';
$contact_heading ??= 'Want early access<br>or a <em>question</em>?';
$contact_lede    ??= 'Drop us a line. We reply to every message — usually within a business day. Tell us which product, and we\'ll add you to that waitlist or get on a call.';
$contact_default_interest ??= '';

// Preselect: sticky form value wins; otherwise the page default.
$contact_selected = $form_data['interest'] !== '' ? $form_data['interest'] : $contact_default_interest;
?>
<section id="contact" aria-labelledby="contact-title">
  <div class="wrap">
    <div class="contact-wrap">
      <div>
        <div class="eyebrow"><?= e($contact_eyebrow) ?></div>
        <h2 id="contact-title" style="margin-top: 16px">
          <?= $contact_heading /* trusted markup */ ?>
        </h2>
        <p class="lede"><?= e($contact_lede) ?></p>

        <div class="ctry">
          <a href="mailto:<?= e($CONFIG['contact_email']) ?>" class="chip-link" aria-label="Email <?= e($CONFIG['contact_email']) ?>">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6" aria-hidden="true"><path d="M3 7l9 6 9-6"/><rect x="3" y="5" width="18" height="14" rx="2"/></svg>
            <?= e($CONFIG['contact_email']) ?>
          </a>
          <a href="<?= e($CONFIG['linkedin']) ?>" target="_blank" rel="noopener noreferrer" class="chip-link" aria-label="Deque Lab on LinkedIn">
            <svg viewBox="0 0 24 24" fill="currentColor" aria-hidden="true"><path d="M4 4h4v4H4zM4 10h4v10H4zM10 10h4v2h.1c.6-1 2-2 4-2 4.3 0 5 2.8 5 6.5V20h-4v-6.4c0-1.5-.5-2.6-2-2.6s-2.4 1.1-2.4 2.6V20h-4z"/></svg>
            LinkedIn
          </a>
          <a href="<?= e($CONFIG['twitter']) ?>" target="_blank" rel="noopener noreferrer" class="chip-link" aria-label="Deque Lab on X (Twitter)">
            <svg viewBox="0 0 24 24" fill="currentColor" aria-hidden="true"><path d="M18.2 3H21l-6.6 7.5L22 21h-6.2l-5-6.4L5 21H2.2l7-8L2 3h6.3l4.5 5.9z"/></svg>
            <?= e($CONFIG['twitter_handle']) ?>
          </a>
        </div>
      </div>

      <div class="form" id="briefForm">
        <?php if ($form_sent): ?>
          <div class="form-success" role="status" aria-live="polite">
            <div class="tick" aria-hidden="true">✓</div>
            <h4>Thanks — got it.</h4>
            <p>We'll reply within a business day at the email you provided.</p>
          </div>
        <?php else: ?>
          <?php if ($form_errors): ?>
            <div class="form-errors" role="alert">
              <ul>
                <?php foreach ($form_errors as $err): ?>
                  <li><?= e($err) ?></li>
                <?php endforeach; ?>
              </ul>
            </div>
          <?php endif; ?>

          <form method="post" action="<?= e($path) ?>#contact" novalidate>
            <input type="hidden" name="form_type" value="contact" />
            <input type="hidden" name="csrf_token" value="<?= e($csrf_token) ?>" />
            <!-- Honeypot: must remain empty -->
            <div class="hp" aria-hidden="true">
              <label for="website">Website (leave blank)</label>
              <input type="text" name="website" id="website" tabindex="-1" autocomplete="off" />
            </div>

            <div class="form-row">
              <div class="grid2">
                <div class="f">
                  <label for="name">Name <span class="req" aria-hidden="true">*</span><span class="sr-only">required</span></label>
                  <input type="text" name="name" id="name" placeholder="Jane Rivera" required maxlength="120"
                         autocomplete="name" value="<?= e($form_data['name']) ?>" />
                </div>
                <div class="f">
                  <label for="email">Email <span class="req" aria-hidden="true">*</span><span class="sr-only">required</span></label>
                  <input type="email" name="email" id="email" placeholder="jane@company.com" required maxlength="180"
                         autocomplete="email" value="<?= e($form_data['email']) ?>" />
                </div>
              </div>
              <div class="f">
                <label for="interest">I'm interested in</label>
                <select name="interest" id="interest">
                  <option value="">— Select a product —</option>
                  <optgroup label="SaaS">
                    <?php foreach ($INTEREST_SAAS as $opt): ?>
                      <option value="<?= e($opt) ?>" <?= $contact_selected === $opt ? 'selected' : '' ?>><?= e($opt) ?></option>
                    <?php endforeach; ?>
                  </optgroup>
                  <optgroup label="Shopify Apps">
                    <?php foreach ($INTEREST_SHOPIFY as $opt): ?>
                      <option value="<?= e($opt) ?>" <?= $contact_selected === $opt ? 'selected' : '' ?>><?= e($opt) ?></option>
                    <?php endforeach; ?>
                  </optgroup>
                  <option value="Just saying hi" <?= $contact_selected === 'Just saying hi' ? 'selected' : '' ?>>Just saying hi</option>
                </select>
              </div>
              <div class="f">
                <label for="msg">Message</label>
                <textarea name="msg" id="msg" maxlength="5000" placeholder="Anything you'd like to share — questions, feedback, or what you're hoping the product does."><?= e($form_data['message']) ?></textarea>
              </div>
            </div>

            <div class="submit">
              <div class="note">No newsletter list. We'll only email you about what you asked about.</div>
              <button type="submit" class="btn btn-accent btn-arrow">Send message</button>
            </div>
          </form>
        <?php endif; ?>
      </div>
    </div>
  </div>
</section>
