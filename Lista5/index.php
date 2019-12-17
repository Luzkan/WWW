<?php include('../Lista5/assets/php/server.php') ?>
<!DOCTYPE HTML>
<html>
	<head>
		<title>Zakamarki Kryptografii</title>
		<meta charset="utf-8" />
		<meta name="viewport" content="width=device-width, initial-scale=1, user-scalable=no" />
		<link rel="stylesheet" href="assets/css/main.css" />
		<noscript><link rel="stylesheet" href="assets/css/noscript.css" /></noscript>
	</head>
	<body class="is-preload">
		<!-- Wrapper -->
		<div id="wrapper">
			<!-- Header -->
			<header id="header" class="alt">
				<h1>Zakamarki Kryptografii</h1>
				
				<!-- Notification Message -->
				<?php if (isset($_SESSION['username'])) : ?>
					<!-- Zalogowany Użytkownik -->
					<p>Witaj <strong><?php echo $_SESSION['username']; ?></strong> na stronie zagadnień kryptograficznych!</p>
					<a href="index.php?logout='1'">Wyloguj</a>
				<?php endif ?> <?php if (!(isset($_SESSION['username']))) : ?>
					<p>Zagadnienia kryptograficzne</p>
					<!-- Login Button -->
					<a href="#" onclick="document.getElementById('modal-wrapper').style.display='block'" style="text-decoration:none">Zaloguj się</a>
				<?php endif ?>
			</header>

			<!-- Nav -->
			<nav id="nav">
				<ul>
					<li><a href="#schematgold" class="active">Schemat Goldwasser-Micali szyfrowania probabilistycznego</a></li>
					<li><a href="#reszta">Reszta/Niereszta kwadratowa</a></li>
					<li><a href="#symbol">Symbol Legendre'a i Jacobiego</a></li>
					<li><a href="#schematprog">Schemat progowy dzielenia sekretu Shamira</a></li>
					<li><a href="#interpolacja">Interpolacja Lagrange'a</a></li>
				</ul>
			</nav>

			<!-- Main -->
			<div id="main">
				<!-- Login Form -->
				<div id="modal-wrapper" class="modal">
					<form class="modal-content animate" action="index.php">
						<h1 style="text-align:center; margin-top: 15px;" >Zaloguj się</h1>
						<div class="container" style="text-align:center;">
						<input type="text" placeholder="Nazwa Użytkownika" name="username" required>
						<input type="password" placeholder="Hasło" name="password" required>        
						<button type="submit" name="login_user">Zaloguj</button>
						<a onclick="document.getElementById('modal-wrapper-register').style.display='block'; document.getElementById('modal-wrapper').style.display='none';" style="text-decoration:none; font-size: 0.8em;"><br />Nie masz konta? Zarejestruj się!</a>
						</div>
					</form>
				</div>

				<!-- Register Form -->
				<div id="modal-wrapper-register" class="modal">
					<form class="modal-content animate" action="index.php">
						<h1 style="text-align:center; margin-top: 15px;" >Zarejestruj się</h1>
						<div class="container" style="text-align:center;">
						<input type="text" placeholder="Nazwa Użytkownika" name="username" required>
						<input type="email" placeholder="E-Mail" name="email" required>
						<input type="password" placeholder="Hasło" name="password_1" required>
						<input type="password" placeholder="Powtórz Hasło" name="password_2" required>        
						<button type="submit" name="reg_user">Zarejestruj</button>
						<a onclick="document.getElementById('modal-wrapper').style.display='block'; document.getElementById('modal-wrapper-register').style.display='none';" style="text-decoration:none; font-size: 0.8em;"><br />Masz już konto? Zaloguj się!</a>
						</div>
					</form>
				</div>

			<!-- Schemat Goldwasser-Micali szyfrowania probabilistycznego -->
			<section id="schematgold" class="main">
				<header class="major">
					<h2>Schemat Goldwasser-Micali szyfrowania probabilistycznego</h2>
				</header>

				<div class="row">
					<div class="col-4 col-12-small">
						<h3>Algorytm generowania kluczy</h3>
						<ol class="algorithm">
							<li>Wybierz losowo dwie duże liczby pierwsze $p$ oraz $q$ (podobnego rozmiaru),</li>
							<li>Policz $n = pq$</li>
							<li>Wybierz $y \in$ $\mathbb{Z}_n$, takie, że $y$ jest nieresztą kwadratową modulo $n$ i symbol Jacobiego
								$\left( \frac{y}{n} = 1 \right)$ (czyli $y$ jest pseudokwadratem modulo $n$),</li>
							<li>Klucz publiczny stanowi para $(n, y)$, zaś odpowiadający mu klucz prywatny to para $(p, q)$.</li>
						</ol>
					</div>
					<div class="col-4 col-12-small">
						<h3>Algorytm deszyfrowania</h3>
						Chcąc zaszyfrować wiadomość $m$ przy uzyciu klucza publicznego $(n, y)$ wykonaj kroki:
						<ol class="algorithm">
							<li>Przedstaw $m$ w postaci łańcycha binarnego $m = m_1m_2...m_t$ długości $t$</li>
							<li><pre><code>For $i$ from $1$ to $t$ do
  wybierz losowe $x ∈ Z^{∗}_{n}$
  If $m_{i} = 1$ then set $c_{i} ← yx^{2}$ $mod$ $n$,
  Otherwise set $c_{i} ← x^{2}$ $mod$ $n$</code></pre></li>
							<li>Kryptogram wiadomości $m$ stanowi $c = (c_1, c_2, ..., c_t)$</li>
						</ol> 	
					</div>
					<div class="col-4 col-12-small">
						<h3>Algorytm deszyfrowania</h3>
						Chcąc odzyskać wiadomości z kryptogramu $c$ przy uzyciu klucza prywatnego $(p, q)$ wykonaj kroki:
						<ol class="algorithm">
							<li><pre><code>For $i$ from $1$ to $t$ do
  policz symbol Legendre’a $e_{i}$ = $(\frac{c_{i}}{p})$,
  If $e_{i}$ = $1$ then set $m_{i} ← 0$
  Otherwise set $m_{i} ← 1$</code></pre></li>
							<li>Zdeszyfrowana wiadomość to $m = m_1m_2...m_t$</li>
						</ol> 	
					</div>
				</div>

				<!-- Show comments if logged in -->
					<div class="row">
						<div class="col-4 col-12-small comments-section">
							<hr>
							<!-- Display total number of comments on this post  -->
							<h4><span id="comments_count">Mamy <?php echo count($comments0) ?></span> komentarzy:</h4>
							<hr>

							<!-- If signed in, present comment form -->
							<?php if (isset($_SESSION['username'])): ?>
								<h3>Skomentuj artykuł!</h3>
								<form class="clearfix" action="index.php" method="post" >
									<textarea name="comment_text"  class="form-control" cols="30" rows="3" required></textarea>
									<input type="text" style="display: none;" name="articleid" value="0">
									<button class="btn btn-primary btn-sm pull-right" name="submit_comment">Skomentuj</button>
								</form>
							<!-- If not logged in, present a sign-in prompt -->
							<?php else: ?>
								<div class="well" style="margin-top: 20px;">
									<h4 class="text-center"><a href="#">Zaloguj się</a> aby skomentować</h4>
								</div>
							<?php endif ?>

						</div>
						<div class="col-6 col-12-medium comments-section">
							<hr>

							<!-- Comment Section -->
							<div id="comments-wrapper">
							<?php if (isset($comments0)): ?>

								<!-- Display Comments -->
								<?php foreach ($comments0 as $comment): ?>

								<!-- Comment -->
								<div class="comment clearfix">
									<img src="assets/img/profile.png" alt="" class="profile_pic">
									<div class="comment-details">
										<span class="comment-name"><?php echo getUsernameById($comment['user_id']) ?></span>
										<span class="comment-date"><?php echo date("F j, Y ", strtotime($comment["created_at"])); ?></span>
										<p><?php echo $comment['body']; ?></p>
										<a class="reply-btn" onclick="replyToComment(this, this.id)" href="javascript:void()" id="<?php echo $comment['id']; ?>">reply</a>
									</div>

									<!-- Reply Form -->
									<form action="index.php" class="reply_form clearfix" id="comment_reply_form_<?php echo $comment['id'] ?>" name="<?php echo $comment['id']; ?>">
										<textarea class="form-control" name="reply_text" cols="30" rows="2" required></textarea>
										<input type="text" style="display: none;" name="cmntid" value="<?php echo $comment['id']; ?>">
										<button class="btn btn-primary btn-xs pull-right submit-reply" name="submit_reply">Submit reply</button>
									</form>

									<!-- Get All Replies -->
									<?php $replies = getRepliesByCommentId($comment['id']) ?>
									<div class="replies_wrapper_<?php echo $comment['id']; ?>">
										<?php if (isset($replies)): ?>
											<?php foreach ($replies as $reply): ?>

												<!-- Reply -->
												<div class="comment reply clearfix">
													<img src="assets/img/profile.png" alt="" class="profile_pic">
													<div class="comment-details">
														<span class="comment-name"><?php echo getUsernameById($reply['user_id']) ?></span>
														<span class="comment-date"><?php echo date("F j, Y ", strtotime($reply["created_at"])); ?></span>
														<p><?php echo $reply['body']; ?></p>
														<a class="reply-btn" href="#">reply</a>
													</div>
												</div>
											<?php endforeach ?>
										<?php endif ?>
									</div>
								</div>
								<?php endforeach ?>

							<!-- No comments detected in Database -->
							<?php else: ?>
								<h2>Bądź pierwszym komentującym ten artykuł! </h2>
							<?php endif ?>

							</div>
						</div>
					</div>
			</section>

			<!-- Reszta/Niereszta z dzielenia -->
			<section id="reszta" class="main">
				<div class="spotlight">
					<div class="content">
						<header class="major">
							<h2>Reszta/Niereszta z dzielenia</h2>
						</header>
						<blockquote><h3><b>Definicja.</b></h3> Niech $a \in$ $\mathbb{Z}_n$. Mówimy, że $a$ jest <i>resztą kwadratową modulo $n$
							(kwadratem modulo $n$)</i>, jeżeli istnieje $x \in$ $\mathbb{Z}^*_n$ takie, że $x^2 \equiv a \ (mod \ p)$. Jeżeli takie
							$x$ nie istnieje, to wówczas $a$ nazywamy <i>nieresztą kwadratową modulo $n$</i>. Zbiór wszystkich reszt
							kwadratowych modulo $n$ oznaczamy $Q_n$, zaś zbiór wszystkich niereszt kwadratowych modulo $n$ oznaczamy
							$\bar{Q}_n$.
						</blockquote>
					</div>
				</div>
			</section>

			<!-- Symbol Legendre’a i Jacobiego -->
			<section id="symbol" class="main">
					<header class="major">
						<h2>Symbol Legendre’a i Jacobiego</h2>
					</header>
					
					<div class="row">
							<div class="col-6 col-12-medium">
								<h3>Faza inicjalizacji:</h3>
								<blockquote><h3><b>Definicja.</b></h3> Niech $p$ będzie nieparzystą liczbą pierwszą, a $a$ liczbą całkowitą.<br>
									<i>Symbol Legendre'a</i> $\left( \frac{a}{p}\right)$ jest zdefiniowany jako:
									$$\left( \frac{a}{p} \right )= \left\{ \begin{array}{lll}
									& \ 0 & \textrm{jeżeli $p | a$}\\
									& \ 1 & \textrm{jeżeli $a \in \ Q_p$}\\
									& -1 & \textrm{jeżeli $a \in \ \bar{Q}_p$}\\
									\end{array} \right.
									$$
								</blockquote>
			
								<p>
									<h3><b>Własności symbolu Legendre'a.</b></h3> Niech $a, b \in \mathbb{Z}$, zaś $p$ to nieparzysta liczba pierwsza.
									Wówczas:
									<ul>
										<li>$\left( \frac{a}{p} \right) \equiv a^{\frac{(p-1)}{2}} (mod \ p)$</li>
										<li>$\left( \frac{ab}{p} \right) = \left( \frac{a}{p} \right) \left( \frac{b}{p} \right)$</li>
										<li>$a \equiv b \ (mod \ p) \Rightarrow \left( \frac{a}{p} \right) = \left( \frac{b}{p} \right)$</li>
										<li>$\left( \frac{2}{p} \right) = (-1)^{\frac{(p^2 - 1)}{8}}$</li>
										<li>Jeżeli $q$ jest nieparzystą liczbą pierwszą inną od $p$ to:
											$$\left( \frac{p}{q} \right) = \left( \frac{q}{p} \right) (-1)^{\frac{(p - 1)(q - 1)}{4}}$$
										</li>
									</ul>
								</p>
							</div>
							<div class="col-6 col-12-medium">
								<h3>Faza łączenia udziałów w sekret:</h3>
								<blockquote><h3><b>Definicja.</b></h3> Niech $n \geq 3$ będzie liczbą nieparzystą, a jej rozkład na czynniki pierwsze to $n =
									p^{e_1}_1 p^{e_2}_2 \ldots p^{e_k}_k$. <i>Symbol Jacobiego</i> $\left( \frac{a}{n} \right)$ jest
									zdefiniowany
									jako:
									$$\left( \frac{a}{n} \right) = \left( \frac{a}{p_1} \right)^{e_1} \left( \frac{a}{p_2} \right)^{e_2} \ldots
									\left( \frac{a}{p_k} \right)^{e_k} $$
									Jeżeli $n$ jest liczbą pierwszą, to symbol Jacobiego jest symbolem Legendre'a.
								</blockquote>
								<p>
									<h3><b>Własności symbolu Jacobiego.</h3></b> Niech $a, b \in \mathbb{Z}$, zaś $m, n \geq 3$ to nieparzyste liczby
									całkowite. Wówczas:
									<ul>
										<li>$\left( \frac{a}{n} \right) = 0, 1$, albo -1. Ponadto $\left( \frac{a}{n} \right) = 0 \iff gcd(a, n)
											\neq 1$</li>
										<li>$\left( \frac{ab}{n} \right) = \left( \frac{a}{n} \right) \left( \frac{b}{n} \right)$</li>
										<li>$\left( \frac{a}{mn} \right) = \left( \frac{a}{m} \right) \left( \frac{a}{n} \right)$</li>
										<li>$a \equiv b \ (mod \ n) \Rightarrow \left( \frac{a}{n} \right) = \left( \frac{b}{n} \right)$</li>
										<li>$\left( \frac{1}{n} \right) = 1$</li>
										<li>$\left( \frac{-1}{n} \right) = (-1)^{\frac{(n - 1)}{2}}$</li>
										<li>$\left( \frac{2}{n} \right) = (-1)^{\frac{(n^2 - 1)}{8}}$</li>
										<li>$\left( \frac{m}{n} \right) = \left( \frac{n}{m} \right) (-1)^{\frac{(m - 1)(n - 1)}{4}}$</li>
									</ul>
									Z własności symbolu Jacobiego wynika, że jeżeli $n$ nieparzyste oraz $a$ nieparzyste i w postaci $a = 2^e
									a_1$,
									gdzie $a_1$ też nieparzyste to:
									$$\left( \frac{a}{n} \right) = \left( \frac{2^e}{n} \right) \left( \frac{a_1}{n} \right) = \left(\frac{2}{n}
									\right)^e \left( \frac{n \ mod \ a_1}{a_1} \right) (-1)^{\frac{(a_1 - 1)(n - 1)}{4}}$$
								</p>
							</div>
						</div>

					<hr />
					<p>
						<b>Algorytm obliczania symbolu Jacobiego $\left( \frac{a}{n} \right)$ (i Legendre'a)</b> dla nieparzystej liczby
						całkowitej $n \geq 3$ oraz całkowitego $0 \leq a \le n$
						<ol class="algorithm">
							<p class="algorithmfront codemath">JACOBI(a, n)</p>
							<li><p class="codemath">If $a = 0$ then return $0$</p></li>
							<li><p class="codemath">If $a = 1$ then return $1$</p></li>
							<li><p class="codemath">Write $a = 2^{e}a_{1}$, gdzie $a_{1}$ nieparzyste</p></li>
							<li><p class="codemath">If $e$ parzyste set $s ← 1$, 
  Otherwise set $s ← 1$ if $n ≡ 1$ $or$ $7 \ (mod \ 8)$, or set
  $s ← −1$ if $n ≡ 3$ $or$ $5 \ (mod \ 8)$</code></li>
							<li><p class="codemath">If $n ≡ 3$ $(mod \ 4)$ $and$ $a1 ≡ 3$ $(mod \ 4)$ then set $s ← −s$</p></li>
							<li><p class="codemath">Set $n_{1} ← n$ $mod$ $a_{1}$</p></li>
							<li><p class="codemath">If $a_{1} = 1$ then return $s$ Otherwise, return $s * JACOBI (n_{1}, a_{1})$</p></li>
							Algorytm działa w czasie $\mathcal{O}((\lg n)^2)$ operacji bitowych.
						</ol>
					</p>

					<!-- Show comments if logged in -->
						<div class="row">
							<div class="col-4 col-12-small comments-section">
								<hr>
								<!-- Display total number of comments on this post  -->

								<?php $comments = getCurrentComments(1); ?>

								<h4><span id="comments_count">Mamy <?php echo count($comments); console_log("test"); console_log($comments); ?></span> komentarzy:</h4>
								<hr>

								<!-- If signed in, present comment form -->
								<?php if (isset($_SESSION['username'])): ?>
									<h3>Skomentuj artykuł!</h3>
									<form class="clearfix" action="index.php" method="post" >
										<textarea name="comment_text" class="form-control" cols="30" rows="3" required></textarea>
										<input type="text" style="display: none;" name="articleid" value="1">
										<button class="btn btn-primary btn-sm pull-right" name="submit_comment">Skomentuj</button>
									</form>
								<!-- If not logged in, present a sign-in prompt -->
								<?php else: ?>
									<div class="well" style="margin-top: 20px;">
										<h4 class="text-center"><a href="#">Zaloguj się</a> aby skomentować</h4>
									</div>
								<?php endif ?>

							</div>
							<div class="col-6 col-12-medium comments-section">
								<hr>

								<!-- Comment Section -->
								<div id="comments-wrapper">
								<?php if (isset($comments1)): ?>

									<!-- Display Comments -->
									<?php foreach ($comments1 as $comment): ?>

									<!-- Comment -->
									<div class="comment clearfix">
										<img src="assets/img/profile.png" alt="" class="profile_pic">
										<div class="comment-details">
											<span class="comment-name"><?php echo getUsernameById($comment['user_id']) ?></span>
											<span class="comment-date"><?php echo date("F j, Y ", strtotime($comment["created_at"])); ?></span>
											<p><?php echo $comment['body']; ?></p>
											<a class="reply-btn" onclick="replyToComment(this, this.id)" href="javascript:void()" id="<?php echo $comment['id']; ?>">reply</a>
										</div>

										<!-- Reply Form -->
										<form action="index.php" class="reply_form clearfix" id="comment_reply_form_<?php echo $comment['id'] ?>" name="<?php echo $comment['id']; ?>">
											<textarea class="form-control" name="reply_text" cols="30" rows="2" required></textarea>
											<input type="text" style="display: none;" name="cmntid" value="<?php echo $comment['id']; ?>">
											<button class="btn btn-primary btn-xs pull-right submit-reply" name="submit_reply">Submit reply</button>
										</form>

										<!-- Get All Replies -->
										<?php $replies = getRepliesByCommentId($comment['id']) ?>
										<div class="replies_wrapper_<?php echo $comment['id']; ?>">
											<?php if (isset($replies)): ?>
												<?php foreach ($replies as $reply): ?>

													<!-- Reply -->
													<div class="comment reply clearfix">
														<img src="assets/img/profile.png" alt="" class="profile_pic">
														<div class="comment-details">
															<span class="comment-name"><?php echo getUsernameById($reply['user_id']) ?></span>
															<span class="comment-date"><?php echo date("F j, Y ", strtotime($reply["created_at"])); ?></span>
															<p><?php echo $reply['body']; ?></p>
															<a class="reply-btn" href="#">reply</a>
														</div>
													</div>
												<?php endforeach ?>
											<?php endif ?>
										</div>
									</div>
									<?php endforeach ?>

								<!-- No comments detected in Database -->
								<?php else: ?>
									<h2>Bądź pierwszym komentującym ten artykuł! </h2>
								<?php endif ?>

								</div>
							</div>
						</div>
				</section>

			<!-- Schemat progowy (t, n) dzielenia sekretu Shamira -->
				<section id="schematprog" class="main">
					<header class="major">
						<h2>Schemat progowy $(t, n)$ dzielenia sekretu Shamira</h2>
						<p> <b>Cel:</b> Zaufana Trzecia Strona $T$ ma sekret $S \geq 0$, który chce podzielić pomiędzy $n$ uczestników
							tak, aby dowolnych $t$ spośród niech mogło sekret odtworzyć.</p>
					</header>

					<div class="row">
						<div class="col-6 col-12-medium">
							<h3>Faza inicjalizacji:</h3>
							<ul>
								<li>$T$ wybiera liczbę pierwszą $p \ge max(S, n)$ i definiuje $a_0 = S$,</li>
								<li>$T$ wybiera losowo i niezależnie $t - 1$ współczynników $a_1, ..., a_{t-1} \in$ $\mathbb{Z}_p$,</li>
								<li>$T$ definiuje wielomian nad $\mathbb{Z}_p$:
									$$f(x) = a_0 + \sum^{t-1}_{j = 1} a_jx^j,$$
								</li>
								<li>Dla $1 \leq i \leq n$ Zaufana Trzecia Strona $T$ wybiera losowo $x_i \in$ $\mathbb{Z}_p$, oblicza:
									$S_i =
									f(x_i) \ mod \ p$ i bezpiecznie przekazuje parę $(x_i, S_i)$ uzytkownikowi $P_i$.</li>
							</ul>
						</div>
						<div class="col-6 col-12-medium">
							<h3>Faza łączenia udziałów w sekret:</h3>
							<p>Dowolna grupa $t$ lub więcej użytkowników łączy swoje udziały - $t$
							róznych punktów $(x_i, S_i)$ wielomianu $f$ i dzięki interpolacji Lagrange'a odzyskuje sekret $S = a_0 =
							f(0)$.</p>
						</div>
					</div>

					<!-- Show comments if logged in -->
						<div class="row">
							<div class="col-4 col-12-small comments-section">
								<hr>
								<!-- Display total number of comments on this post  -->
								<h4><span id="comments_count">Mamy <?php echo count($comments2) ?></span> komentarzy:</h4>
								<hr>

								<!-- If signed in, present comment form -->
								<?php if (isset($_SESSION['username'])): ?>
									<h3>Skomentuj artykuł!</h3>
									<form class="clearfix" action="index.php" method="post" >
										<textarea name="comment_text"  class="form-control" cols="30" rows="3" required></textarea>
										<input type="text" style="display: none;" name="articleid" value="2">
										<button class="btn btn-primary btn-sm pull-right" name="submit_comment">Skomentuj</button>
									</form>
								<!-- If not logged in, present a sign-in prompt -->
								<?php else: ?>
									<div class="well" style="margin-top: 20px;">
										<h4 class="text-center"><a href="#">Zaloguj się</a> aby skomentować</h4>
									</div>
								<?php endif ?>

							</div>
							<div class="col-6 col-12-medium comments-section">
								<hr>

								<!-- Comment Section -->
								<div id="comments-wrapper">
								<?php if (isset($comments2)): ?>

									<!-- Display Comments -->
									<?php foreach ($comments2 as $comment): ?>

									<!-- Comment -->
									<div class="comment clearfix">
										<img src="assets/img/profile.png" alt="" class="profile_pic">
										<div class="comment-details">
											<span class="comment-name"><?php echo getUsernameById($comment['user_id']) ?></span>
											<span class="comment-date"><?php echo date("F j, Y ", strtotime($comment["created_at"])); ?></span>
											<p><?php echo $comment['body']; ?></p>
											<a class="reply-btn" onclick="replyToComment(this, this.id)" href="javascript:void()" id="<?php echo $comment['id']; ?>">reply</a>
										</div>

										<!-- Reply Form -->
										<form action="index.php" class="reply_form clearfix" id="comment_reply_form_<?php echo $comment['id'] ?>" name="<?php echo $comment['id']; ?>">
											<textarea class="form-control" name="reply_text" cols="30" rows="2" required></textarea>
											<input type="text" style="display: none;" name="cmntid" value="<?php echo $comment['id']; ?>">
											<button class="btn btn-primary btn-xs pull-right submit-reply" name="submit_reply">Submit reply</button>
										</form>

										<!-- Get All Replies -->
										<?php $replies = getRepliesByCommentId($comment['id']) ?>
										<div class="replies_wrapper_<?php echo $comment['id']; ?>">
											<?php if (isset($replies)): ?>
												<?php foreach ($replies as $reply): ?>

													<!-- Reply -->
													<div class="comment reply clearfix">
														<img src="assets/img/profile.png" alt="" class="profile_pic">
														<div class="comment-details">
															<span class="comment-name"><?php echo getUsernameById($reply['user_id']) ?></span>
															<span class="comment-date"><?php echo date("F j, Y ", strtotime($reply["created_at"])); ?></span>
															<p><?php echo $reply['body']; ?></p>
															<a class="reply-btn" href="#">reply</a>
														</div>
													</div>
												<?php endforeach ?>
											<?php endif ?>
										</div>
									</div>
									<?php endforeach ?>

								<!-- No comments detected in Database -->
								<?php else: ?>
									<h2>Bądź pierwszym komentującym ten artykuł! </h2>
								<?php endif ?>

								</div>
							</div>
						</div>
				</section>

			<!-- Interpolacja Lagrange'a -->
				<section id="interpolacja" class="main special">
					<header class="major">
						<h2>Interpolacja Lagrange'a</h2>
					</header>
					<p class="content">
						Mając dane $t$ różnych punktów $(x_i, y_i)$ nienanego wielomianu $f$ stopnia mniejszego od $t$ możemy
						policzyć
						jego współczynniki korzystając ze wzoru:
						$$f(x) = \sum^t_{i = 1}\left( y_i \prod_{1 \leq j \leq t, j\neq i} \frac{x - x_j}{x_i - x_j} \right) mod \ 
						p$$<br /><br />
						<b>Wskazówka:</b> w schemacie Shamira, aby odzyskać sekret <i>S</i>, użytkownicy nie muszą znać całego
						wielomianu $f$. Wsstawiając do wzoru na iterpolację Lagrange'a $x = 0$, dostajemy wersję uproszczoną, ale
						wystarczającą aby policzyć sekret $S = f(0)$:
						$$f(x) = \sum^t_{i = 1} \left(y_i \prod_{1 \leq j \leq t, j\neq i} \frac{x_j}{x_j - x_i} \right) mod \ p$$
					</p>
				</section>
			</div>

			<!-- Footer -->
			<footer id="footer">
				<p class="copyright">Unique Visitors: <?php include '../Lista5/assets/php/counter.php';?><br />&copy; Lista 4. Design: <a href="https://github.com/Luzkan">Marcel Jerzyk</a>.</p>
			</footer>
		</div>

		<!-- Scripts -->
		<script id="MathJax-script" async src="https://cdn.jsdelivr.net/npm/mathjax@3/es5/tex-mml-chtml.js"></script>
		<script src="assets/js/main.js"></script>
		<script src="assets/js/popup.js"></script>
	</body>
</html>
