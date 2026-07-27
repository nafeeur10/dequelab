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
    <div class="sec-head">
      <div>
        <div class="eyebrow"><?= e($contact_eyebrow) ?></div>
        <h2 id="contact-title" style="margin-top: 14px">
          <?= $contact_heading /* trusted markup */ ?>
        </h2>
      </div>
    </div>

    <div class="contact-wrap">
      <div>
        <p class="lede"><?= e($contact_lede) ?></p>

        <div class="talk">
          <h3 class="talk-title">Prefer to talk to a <em>human</em>?</h3>
          <p class="talk-sub">I'm Nafeeur, the founder. Email or call me directly — I read every message myself and reply personally, usually within a business day.</p>
          <div class="person">
            <img class="person-avatar" src="/assets/10268310.png" width="112" height="112"
                 alt="Nafeeur Rahman" loading="lazy" decoding="async" />
            <div class="person-info">
              <div class="person-name">Nafeeur Rahman</div>
              <div class="person-role">Founder</div>
              <div class="person-contacts">
                <a href="tel:+8801793445766">+880 1793 445766</a>
                <a href="mailto:nafeeur@dequelab.com">nafeeur@dequelab.com</a>
                <a href="mailto:inafeeur@gmail.com">inafeeur@gmail.com</a>
              </div>
            </div>
          </div>
        </div>

        <div class="ctry">
          <a href="mailto:<?= e($CONFIG['contact_email']) ?>" class="ico-link" aria-label="Email us">
            <img src="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAACgAAAAoCAYAAACM/rhtAAAACXBIWXMAAAsTAAALEwEAmpwYAAABhklEQVR4nO2Vy0tCQRjF73/nSouI3i1qFUEQLYoWbaIyytRS7x2DaFMQ0UKCNqHSw9B0ih4KBRERQUILk8LwcTkx4y2Kinx3sTlwYJgZmN/H9xhJEhISqgMZpoPQo6WPgISqurJBAFIBqOoLsNMdg9mX/HMwsy/JWb4Ajm09oMl+jO7FGGZ2nmoOZtl7Ru/yJRptRxjxxL9PsRzO8cMGK+WXbYFU1cFsBy/oW7mCyUoxtHEHOZz9vQadhxkMrt/COBfBwNoNFkLpioM5QxkOxN7oX72GI5guvknsgc/RubToyrGsZcmkZcm6nyq/i2d3tfqw5+tDobni4SJqvs7nC6vzksbMFOswEkOz44Q/RgqEG99OoNV5hjblHBPeRPXnIHuwxXWKdhLFpPfxx3tmfxJd7uh7QEotBzVL8+hmnKe9Z+mCQ7NmYmZrtsfO2J1SSqJiP4kczmLYc48OJQqjJcLN1mzvbWT8j6+O6ACKCEAqAFXd2CAAqQBUBSApBlCPloSEhOpAr54GgjiuxPL7AAAAAElFTkSuQmCC" alt="new-post">  
          </a>
          <a href="<?= e($CONFIG['linkedin']) ?>" target="_blank" rel="noopener noreferrer" class="ico-link" aria-label="Deque Lab on LinkedIn">
            <img src="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAADAAAAAwCAYAAABXAvmHAAAACXBIWXMAAAsTAAALEwEAmpwYAAABwklEQVR4nO2ZP0vDQByGkwsOruIkuGpXP4GLWyuufgk/g4OUXHFQEId2cFFwcHJUsC1mFPGutNpCLdY6VIp/2kT7N/1J0latbbGJklzgHninHMf75HdZLoLA4XA4TCPJdAXJJI5kqiFMwZHIVBMxjUkyWf5TeRET7FhpPDwiJkH7b97l8qgbKUQClgXMY8NAeWRMQaZR6wKYqG4XR73IpGJDgIHi+Cv/JuCLpOG8oIHW0EEpaDAfTntLQClo8J34veYtAa2h9wmodd1bAorXJ+CLpE0JYxKxvApz4RtvCSCXInAB3Mkwxnk+u3sN2xclSJWqUG224bXWMo/i2ukDTG4m2BZY2MvA41sTRnFZfIeZnRS7AtmXOvxGLK+CxKrAuPiPcmwK6G0wz//S4S2sHucheqcOXbeffGZTYF0p9q2ZCCXgJFcZWJd5qrEpML2dHNhn8SA7sK5ca7En0P7xvJepreTYa12fgN29EBfAXMCEC2AuQLkA4gIWGbWRWxE8fbWIadmygHE/z0BxMCJiemZZwPi54HZx1I2Er/yWBTpTIEG3y4uYbNgq/zmJEAkY9/POfhNENY6N7TfP4XA4glN8AJJwntfiYf8PAAAAAElFTkSuQmCC" alt="linkedin">  
          </a>
          <a href="<?= e($CONFIG['twitter']) ?>" target="_blank" rel="noopener noreferrer" class="ico-link" aria-label="Deque Lab on X">
            <img src="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAADIAAAAyCAYAAAAeP4ixAAAACXBIWXMAAAsTAAALEwEAmpwYAAAC+klEQVR4nO2ZSWhUQRCGP424K24J7oiCOyoeBAUVPAQEEQlxuehBD4ogBD3pyZjcNOjJmwt6EEXwIgriRUGiYkxEQVDcUC+iMZt7kpaCP9CMSd6bl2ReT5wfipmp6aqpf17339XvQQEFFNAXlADVQD3QBrgcWxvwGKhSLYmwFWhJoXjXg1kt5UlIdAZQvMuwzmzIlAR2JVyGNQPFcYhUB1Csi7BjcYg0BFCoizATgEi0BlCoizCb+pFweWKR+K+IVETkOJdFQV+A+Yo7kGsiP4FlveQYC7yIkec3sEExG4E/aUytZ8BIYAhww/NvV541QHvE5rZLY5cATWmukeOKmQ58lu8rMFv+qhh7wTTgXVprxMk6vKmxxfPfAYYCw4D73cRd0ZUcBTxIQML1NxGzD8AkxZ71/Aflm5exNz0ERovotYQk3EAQMbuq2DHeIjdBWC7/PvneeO14TR9IuIEiYrZT8as99XkqQTBcABbp/d4+knADScRUZ45yHPX8JzPyl2Ypsy7XRMzuAkVa5LWezFrxhvEJZNalQcTssPLM9c41H4HJ8p/OFyJ/gFXKtcfzm0IhxXqeD0ScCrWCDZc9/275VgK/8oHIW09mp2hqOe0ntq8YjoROpBlYqlwT9Vrq3cyolRgUSRyCJNIObFKedcB7YKo+n/LGmTwbZgGNIRLZrxwLvQJvqq8aATzxBME2TtQBB0WkRvEmsy8zvrOdvKtd/yHfK2Cc/JdCIXJdc97akXvdfP8NWKD8hzz/GfkmJGjlI5EtiTo1izZ9LvYy7hEwXF3vbc+/zVtTHWkRMVmdGeMQ1WU2xjDDO4g1atEbTqRBxPaEFYrZEfNesf3j6xVT5vlveYLQkEsiJrObNX6tzh5x/4DXah57wmLge66I2G2brsbwU4J1dT6ihopcr5E0LRKDhkhLAEW6GP1cJOoDKNT1x2OFOPtB2lY5GB69NemMEwvlAT8MLYtLwifTHNiVKCMhinWzuS6lR3Kt+u3KbKZTAQUUwD/4C68XAvRNax0sAAAAAElFTkSuQmCC" alt="twitterx--v1">  
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
              <button type="submit" class="btn btn-accent btn-arrow">Send message</button>
            </div>
          </form>
        <?php endif; ?>
      </div>
    </div>
  </div>
</section>
