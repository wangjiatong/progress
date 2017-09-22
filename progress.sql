SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;


CREATE TABLE company (
  id int(11) NOT NULL AUTO_INCREMENT COMMENT '公司id',
  company_name varchar(30) COLLATE utf8_unicode_ci NOT NULL COMMENT '公司名称',
  PRIMARY KEY (id)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

INSERT INTO company (id, company_name) VALUES
(1, '翌银玖德'),
(2, '晨曦基金'),
(3, '金江租赁');

CREATE TABLE migration (
  version varchar(180) COLLATE utf8_unicode_ci NOT NULL,
  apply_time int(11) DEFAULT NULL,
  PRIMARY KEY (version)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

INSERT INTO migration (version, apply_time) VALUES
('m000000_000000_base', 1498033295);

CREATE TABLE new_project (
  id int(11) NOT NULL AUTO_INCREMENT COMMENT '新项目-用户 主键',
  project_id int(11) NOT NULL COMMENT '新项目id',
  user_id int(11) NOT NULL COMMENT '参与人id',
  is_new tinyint(4) NOT NULL DEFAULT '1' COMMENT '是否为新项目 1=>是，0=>否',
  PRIMARY KEY (id)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci COMMENT='新项目与用户关系表';

INSERT INTO new_project (id, project_id, user_id, is_new) VALUES
(8, 71, 24, 1);

CREATE TABLE progress (
  id int(11) NOT NULL AUTO_INCREMENT COMMENT '项目进展id',
  project_id int(11) NOT NULL COMMENT '项目id',
  speaker_id int(11) NOT NULL COMMENT '汇报人Id',
  `comment` text COLLATE utf8_unicode_ci NOT NULL COMMENT '汇报内容',
  created_at varchar(30) COLLATE utf8_unicode_ci NOT NULL COMMENT '创建时间',
  PRIMARY KEY (id)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci COMMENT='项目进展';

INSERT INTO progress (id, project_id, speaker_id, `comment`, created_at) VALUES
(53, 71, 1, '<p>ddddd</p>', '2017-06-22 15:40:01'),
(54, 71, 1, '<p>aaaaaaaaaaaa</p>', '2017-06-22 15:41:21'),
(55, 71, 1, '<p>aaaaaaaaaaa</p>', '2017-06-22 15:41:42'),
(51, 70, 1, '<p>dddd</p>', '2017-06-20 16:43:33'),
(52, 70, 1, '<p>dddddddddd</p>', '2017-06-20 17:12:43');

CREATE TABLE project (
  id int(11) NOT NULL AUTO_INCREMENT COMMENT '项目id',
  project_name varchar(30) COLLATE utf8_unicode_ci NOT NULL COMMENT '项目名称',
  project_content text COLLATE utf8_unicode_ci NOT NULL COMMENT '项目内容',
  starter int(11) NOT NULL COMMENT '发起人id',
  partner varchar(255) COLLATE utf8_unicode_ci NOT NULL COMMENT '参与人id数组',
  created_at varchar(30) COLLATE utf8_unicode_ci NOT NULL COMMENT '创建时间',
  `status` tinyint(4) NOT NULL DEFAULT '1' COMMENT '项目状态，默认为 1=> 运作中， 0=>已结束',
  PRIMARY KEY (id)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci COMMENT='项目表';

INSERT INTO project (id, project_name, project_content, starter, partner, created_at, `status`) VALUES
(70, 'dddddddddddddd', '<p>dddddddddd1111</p>', 1, '1', '2017-06-20', 1),
(71, 'YII2 的授权（Authorization）', '<h1>Authorization 授权</h1><blockquote>Note: This section is under development.<br>注意：这一节还在完善中。<br></blockquote><p>Authorization is the process of verifying that a user has enough permission to do something. Yii provides two authorization methods: Access Control Filter (ACF) and Role-Based Access Control (RBAC).</p><p>授权是用来检验一个用户有足够权限来做些事情的举措。Yii提供两种授权方法：访问控制过滤（ACF)和基于角色的访问控制（RBAC)。</p><h2><a name="t2" target="_blank"></a><a target="_blank" name="user-content-access-control-filter-%E8%AE%BF%E9%97%AE%E6%8E%A7%E5%88%B6%E8%BF%87%E6%BB%A4" class="anchor" href="https://github.com/lujisheng/yii2/blob/master/docs/guide/security-authorization.md#access-control-filter-%E8%AE%BF%E9%97%AE%E6%8E%A7%E5%88%B6%E8%BF%87%E6%BB%A4"></a>Access Control Filter 访问控制过滤</h2><p>Access Control Filter (ACF) is a simple authorization method that is best used by applications that only need some simple access control. As its name indicates, ACF is an action filter that can be attached to a controller or a module as a behavior. ACF will check a set of [[yii\\filters\\AccessControl::rules|access rules]] to make sure the current user can access the requested action.</p><p>访问控制过滤（ACF)是一个简单的授权方法，非常适合于在只需要一些简单访问控制的程序中使用。正如名字所标示的，访问控制过滤（ACF)是一个动作过滤，它可以作为一个扩展附加到一个控制器或是一个模块。访问控制过滤（ACF)将会检查一套[[yii\\filters\\AccessControl::rules|accessrules]] 来确定当前用户能够访问请求的动作。</p><p>The code below shows how to use ACF which is implemented as [[yii\\filters\\AccessControl]]:</p><p>下面的代码显示如何使用访问控制过滤（ACF)，它已经被实现为[[yii\\filters\\AccessControl]]：</p><pre><span class="k">use</span> <span class="nx">yii\\filters\\AccessControl</span><span class="p">;</span> <span class="k">class</span> <span class="nc">SiteController</span> <span class="k">extends</span> <span class="nx">Controller</span> <span class="p">{</span> <span class="k">public</span> <span class="k">function</span> <span class="nf">behaviors</span><span class="p">()</span> <span class="p">{</span> <span class="k">return</span> <span class="p">[</span> ''access'' <span class="o">=&gt;</span> <span class="p">[</span> ''class'' <span class="o">=&gt;</span> <span class="nx">AccessControl</span><span class="o">::</span><span class="na">className</span><span class="p">(),</span> ''only'' <span class="o">=&gt;</span> <span class="p">[</span>''login''<span class="p">,</span> ''logout''<span class="p">,</span> ''signup''<span class="p">],</span> ''rules'' <span class="o">=&gt;</span> <span class="p">[</span> <span class="p">[</span> ''allow'' <span class="o">=&gt;</span> <span class="k">true</span><span class="p">,</span> ''actions'' <span class="o">=&gt;</span> <span class="p">[</span>''login''<span class="p">,</span> ''signup''<span class="p">],</span> ''roles'' <span class="o">=&gt;</span> <span class="p">[</span>''?''<span class="p">],</span> <span class="p">],</span> <span class="p">[</span> ''allow'' <span class="o">=&gt;</span> <span class="k">true</span><span class="p">,</span> ''actions'' <span class="o">=&gt;</span> <span class="p">[</span>''logout''<span class="p">],</span> ''roles'' <span class="o">=&gt;</span> <span class="p">[</span>''@''<span class="p">],</span> <span class="p">],</span> <span class="p">],</span> <span class="p">],</span> <span class="p">];</span> <span class="p">}</span> <span class="c1">// ...</span> <span class="p">}</span> </pre><p>In the code above ACF is attached to the <code>site</code> controller as a behavior. This is the typical way of using an action filter. The<code>only</code> option specifies that the ACF should only be applied to<code>login</code>,<code>logout</code> and<code>signup</code> actions. The <code>rules</code> option specifies the [[yii\\filters\\AccessRule|access rules]], which reads as follows:</p><p>在上面的代码中，访问控制过滤（ACF)作为一个扩展附加到site控制器中。这是一个典型使用动作过滤的方式。only选项指定访问控制过滤（ACF)将只能用于登录、登出和注册动作。rules选项指定[[yii\\filters\\AccessRule|accessrules]]，它应作如下理解：</p><ul><li>Allow all guest (not yet authenticated) users to access ''login'' and ''signup'' actions. The<code>roles</code> option contains a question mark<code>?</code> which is a special token recognized as "guests".</li><li>允许所有的访客（没有授权过的）、用户访问登录和注册动作。roles选项包含一个问号（？），它是一个标志，代表着“访客”。</li><li>Allow authenticated users to access ''logout'' action. The <code>@</code> character is another special token recognized as authenticated users.</li><li>允许已授权的用户访问登出动作。字符@是另一个标志，代表着已授权用户。</li></ul><p>When ACF performs authorization check, it will examine the rules one by one from top to bottom until it finds a match. The<code>allow</code> value of the matching rule will then be used to judge if the user is authorized. If none of the rules matches, it means the user is NOT authorized and ACF will stop further action execution.</p><p>当访问控制过滤（ACF)进行授权检查时，它会从上到下地一个接着一个地<a href="http://lib.csdn.net/base/softwaretest" class="replace_word" title="软件测试知识库" target="_blank">测试</a>那些规则直至找到一个相符的。相符的允许值将立即用于判断用户是否被授权。如果没有一个规则符合，这就意味着这个用户“没有”被授权，访问控制过滤（ACF)将阻止它进一步的动作请求。</p><p>By default, ACF does only of the followings when it determines a user is not authorized to access the current action:</p><p>默认情况下，当访问控制过滤（ACF)探测到一个用户没有被授权进入当前动作时，它只会作下面的工作：</p><ul><li>If the user is a guest, it will call [[yii\\web\\User::loginRequired()]], which may redirect the browser to the login page.</li><li>如果这个用户是个访客，ACF将会调用[[yii\\web\\User::loginRequired()]]，将浏览器重定向到登录页面。</li><li>If the user is already authenticated, it will throw a [[yii\\web\\ForbiddenHttpException]].</li><li>如果用户已有授权，ACF会抛出一个[[yii\\web\\ForbiddenHttpException]]。</li></ul><p>You may customize this behavior by configuring the [[yii\\filters\\AccessControl::denyCallback]] property:</p><p>你可以通过定义配置[[yii\\filters\\AccessControl::denyCallback]]的属性来对这个扩展进行自定义：</p><pre><span class="p">[</span> ''class'' <span class="o">=&gt;</span> <span class="nx">AccessControl</span><span class="o">::</span><span class="na">className</span><span class="p">(),</span> ''denyCallback'' <span class="o">=&gt;</span> <span class="k">function</span> <span class="p">(</span><span class="nv">$rule</span><span class="p">,</span> <span class="nv">$action</span><span class="p">)</span> <span class="p">{</span> <span class="k">throw</span> <span class="k">new</span> <span class="nx">\\Exception</span><span class="p">(</span>''You are not allowed to access this page 您没有被允许访问这个页面！''<span class="p">);</span> <span class="p">}</span> <span class="p">]</span> </pre><p>[[yii\\filters\\AccessRule|Access rules]] support many options. Below is a summary of the supported options. [[yii\\filters\\AccessRule|Access rules]]支持许多选项。下面是它支持的选项的概览。</p><p>You may also extend [[yii\\filters\\AccessRule]] to create your own customized access rule classes.</p><p>你也可以扩展[[yii\\filters\\AccessRule]]来创建你自己定义的访问规则类。</p><ul><li>[[yii\\filters\\AccessRule::allow|allow]]: specifies whether this is an "allow" or "deny" rule.</li><li>[[yii\\filters\\AccessRule]]：指定是一个“允许”规则还是一个“禁止”规则。</li><li>[[yii\\filters\\AccessRule::actions|actions]]: specifies which actions this rule matches. This should be an array of action IDs. The comparison is case-sensitive. If this option is empty or not set, it means the rule applies to all actions.</li><li>[[yii\\filters\\AccessRule]]：指定这个规则与哪些动作匹配。这应该是一个动作的ID的数组。比较是区分大小写的。如果这个选项是空的或是没有设置，这意味着这个规则适用于所有的动作。</li><li>[[yii\\filters\\AccessRule::controllers|controllers]]: specifies which controllers this rule matches. This should be an array of controller IDs. The comparison is case-sensitive. If this option is empty or not set, it means the rule applies to all controllers.</li><li>[[yii\\filters\\AccessRule::controllers|controllers]]：指定这个规则与哪些控制器匹配。这应该是一个控制器的ID的数组。比较是区分大小写的。如果这个选项是空的或是没有设置，这意味着这个规则适用于所有的控制器。</li><li>[[yii\\filters\\AccessRule::roles|roles]]: specifies which user roles that this rule matches. Two special roles are recognized, and they are checked via [[yii\\web\\User::isGuest]]:</li><li>[[yii\\filters\\AccessRule::roles|roles]]：指定这个规则与哪些用户角色匹配。两个特别的用户角色已经被确立，它们将通过[[yii\\web\\User::isGuest]]来检查。<ul><li><code>?</code>: matches a guest user (not authenticated yet)</li><li>？：匹配访客（还没有被授权）</li><li><code>@</code>: matches an authenticated user</li><li>@：匹配一个已授权用户</li></ul></li></ul><p>Using other role names requires RBAC (to be described in the next section), and [[yii\\web\\User::can()]] will be called. If this option is empty or not set, it means this rule applies to all roles.</p><p>使用其他角色名称（需要基于角色的访问控制（RBAC)，将在下一节说明）的已授权用户，将会调用[[yii\\web\\User::can()]]。如果这个选项是空的或是没有设置，这意味着这个规则适用于所有的角色。</p><ul><li>[[yii\\filters\\AccessRule::ips|ips]]: specifies which [[yii\\web\\Request::userIP|client IP addresses]] this rule matches. An IP address can contain the wildcard<code>*</code> at the end so that it matches IP addresses with the same prefix. For example, ''192.168.*'' matches all IP addresses in the segment ''192.168.''. If this option is empty or not set, it means this rule applies to all IP addresses.</li><li>[[yii\\filters\\AccessRule::ips|ips]]：指定这个规则匹配哪一个[[yii\\web\\Request::userIP|clientIP addresses]]。一个IP地址可以在末尾包含一个通配符<em>，这样就可以匹配具有相同前缀的所有IP地址。比如，“192.168.</em>”，匹配“192.168.”节中所有的IP地址。如果这个选项是空的或是没有设置，这意味着这个规则适用于所有的IP。</li><li>[[yii\\filters\\AccessRule::verbs|verbs]]: specifies which request method (e.g.<code>GET</code>,<code>POST</code>) this rule matches. The comparison is case-insensitive.</li><li>[[yii\\filters\\AccessRule::verbs|verbs]]:指定这个规则匹配哪种请求方法（比如GET，POST)。比较是区分大小写的。</li><li>[[yii\\filters\\AccessRule::matchCallback|matchCallback]]: specifies a PHP callable that should be called to determine if this rule should be applied.</li><li>[[yii\\filters\\AccessRule::matchCallback|matchCallback]]：指定一个PHP可调用，它将被调用来检查这个规则是否可以执行。</li><li>[[yii\\filters\\AccessRule::denyCallback|denyCallback]]: specifies a PHP callable that should be called when this rule will deny the access.</li><li>[[yii\\filters\\AccessRule::denyCallback|denyCallback]]：指定一个PHP可调用，在这个规则拒绝访问时它将被调用。</li></ul><p>Below is an example showing how to make use of the <code>matchCallback</code> option, which allows you to write arbitrary access check logic:</p><p>下面是一个例子，显示如何使用matchCallback选项，它将能让你写出任意访问控制逻辑：</p><pre><span class="k">use</span> <span class="nx">yii\\filters\\AccessControl</span><span class="p">;</span> <span class="k">class</span> <span class="nc">SiteController</span> <span class="k">extends</span> <span class="nx">Controller</span> <span class="p">{</span> <span class="k">public</span> <span class="k">function</span> <span class="nf">behaviors</span><span class="p">()</span> <span class="p">{</span> <span class="k">return</span> <span class="p">[</span> ''access'' <span class="o">=&gt;</span> <span class="p">[</span> ''class'' <span class="o">=&gt;</span> <span class="nx">AccessControl</span><span class="o">::</span><span class="na">className</span><span class="p">(),</span> ''only'' <span class="o">=&gt;</span> <span class="p">[</span>''special-callback''<span class="p">],</span> ''rules'' <span class="o">=&gt;</span> <span class="p">[</span> <span class="p">[</span> ''actions'' <span class="o">=&gt;</span> <span class="p">[</span>''special-callback''<span class="p">],</span> ''allow'' <span class="o">=&gt;</span> <span class="k">true</span><span class="p">,</span> ''matchCallback'' <span class="o">=&gt;</span> <span class="k">function</span> <span class="p">(</span><span class="nv">$rule</span><span class="p">,</span> <span class="nv">$action</span><span class="p">)</span> <span class="p">{</span> <span class="k">return</span> <span class="nb">date</span><span class="p">(</span>''d-m''<span class="p">)</span> <span class="o">===</span> ''31-10''<span class="p">;</span> <span class="p">}</span> <span class="p">],</span> <span class="p">],</span> <span class="p">],</span> <span class="p">];</span> <span class="p">}</span> <span class="c1">// Match callback called! This page can be accessed only each October 31st</span> <span class="c1">// Match callback被调用！这个页面只能在每一个十月31日才能访问</span> <span class="k">public</span> <span class="k">function</span> <span class="nf">actionSpecialCallback</span><span class="p">()</span> <span class="p">{</span> <span class="k">return</span> <span class="nv">$this</span><span class="o">-&gt;</span><span class="na">render</span><span class="p">(</span>''happy-halloween''<span class="p">);</span> <span class="p">}</span> <span class="p">}</span></pre>', 1, '24, 1', '2017-06-22', 1);

CREATE TABLE project_user (
  id int(11) NOT NULL AUTO_INCREMENT COMMENT '主键',
  project_id int(11) NOT NULL COMMENT '项目id',
  user_id int(11) NOT NULL COMMENT '用户id',
  PRIMARY KEY (id)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci COMMENT='项目和用户关系表';

INSERT INTO project_user (id, project_id, user_id) VALUES
(80, 71, 1),
(79, 71, 24),
(75, 70, 1);

CREATE TABLE unread_message (
  id int(11) NOT NULL AUTO_INCREMENT COMMENT '未读消息id',
  project_id int(11) NOT NULL COMMENT '项目id',
  progress_id int(11) NOT NULL COMMENT '项目进展id',
  user_id int(11) NOT NULL COMMENT '用户id',
  `status` tinyint(4) NOT NULL DEFAULT '0' COMMENT '消息状态',
  created_at varchar(30) COLLATE utf8_unicode_ci NOT NULL COMMENT '创建时间',
  PRIMARY KEY (id)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci COMMENT='未读消息';

INSERT INTO unread_message (id, project_id, progress_id, user_id, `status`, created_at) VALUES
(95, 71, 55, 24, 0, '2017-06-22 15:41:42'),
(93, 71, 53, 24, 0, '2017-06-22 15:40:01'),
(94, 71, 54, 24, 0, '2017-06-22 15:41:21');

CREATE TABLE `user` (
  id int(11) NOT NULL AUTO_INCREMENT COMMENT '用户Id',
  username varchar(30) COLLATE utf8_unicode_ci NOT NULL COMMENT '用户账号',
  passwd varchar(30) COLLATE utf8_unicode_ci NOT NULL COMMENT '用户密码',
  email varchar(30) COLLATE utf8_unicode_ci NOT NULL COMMENT '电子邮箱',
  phone varchar(30) COLLATE utf8_unicode_ci NOT NULL COMMENT '电话',
  `name` varchar(10) COLLATE utf8_unicode_ci NOT NULL COMMENT '用户姓名',
  is_admin tinyint(4) NOT NULL DEFAULT '0' COMMENT '是否为管理员：0=>否，1=>是',
  `status` tinyint(4) NOT NULL DEFAULT '0' COMMENT '账号是否激活：0=>否，1=>是',
  company tinyint(4) NOT NULL COMMENT '所属公司',
  auth_key varchar(32) COLLATE utf8_unicode_ci NOT NULL COMMENT '自动登录auth_key',
  PRIMARY KEY (id)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci COMMENT='用户';

INSERT INTO `user` (id, username, passwd, email, phone, `name`, is_admin, `status`, company, auth_key) VALUES
(1, 'admin', 'ewinjj6688', '11228463@qq.com', '18603815425', '王迦童', 1, 1, 1, '1'),
(24, 'test', 'test', 'test@test.com', 'test', 'test', 0, 1, 1, 'Zmmz5b-VNQN3jZDwWjo_AkrlWgEUQuvT');

CREATE TABLE user_company (
  id int(11) NOT NULL AUTO_INCREMENT COMMENT '用户_公司-主键',
  user_id int(11) NOT NULL COMMENT '用户id',
  company_id int(11) NOT NULL COMMENT '公司id',
  PRIMARY KEY (id)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci COMMENT='用户与公司关系表';

INSERT INTO user_company (id, user_id, company_id) VALUES
(14, 24, 1),
(13, 1, 1);

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
