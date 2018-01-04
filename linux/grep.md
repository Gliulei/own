 [:alnum:] - 字母数字字符
 [:alpha:] - 字母字符
 [:blank:] - 空字符: 空格键符 和 制表符
 [:digit:] - 数字: '0 1 2 3 4 5 6 7 8 9'
 [:lower:] - 小写字母: 'a b c d e f g h i j k l m n o p q r s t u v w x y z'
 [:space:] - 空格字符: 制表符、换行符、垂直制表符、换页符、回车符和空格键符
 [:upper:] - 大写字母: 'A B C D E F G H I J K L M N O P Q R S T U V W X Y Z'
 
 {N,}	匹配前面的子表达式 N 次到多次。
 {N,M}	匹配前面的子表达式 N 到 M 次，至少 N 次至多 M 次。
 
 **例子**
 匹配comment:数字:profile 这种格式 例子:comment:3545:profile
 
 cat demo.txt | grep 'comment:[[:digit:]]\{1,\}:profile