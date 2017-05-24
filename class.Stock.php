<?php
class Stock{
  function __construct(){
    $this->name = ucwords($this->random_name(random_int(1,3)));
    $this->symbol = strtoupper(substr($this->name, 0, random_int(3, 4)));
    $this->price = number_format((random_int(1, 500)/3), 2);
    $this->price_history = array($this->price);
    $this->change = 0;
    $this->news = false;
    $this->active = true;
  }

  function update_price($change=false){
    if(!$this->active) return $this->price;
    if($change)
      $this->change = $change + (random_int(0, 100)/100);
    else{
      $this->change = random_int(-3, 3) + (random_int(0, 100)/100);
    }
    $this->price = sprintf("%.2f", ($this->price + ($this->price * ($this->change / 100))) );
    $this->price_history[] = $this->price;
    $this->active = $this->price > 0;

    return $this->price;
  }

  function update_news($story=false){
    $this->news = $story;
  }

  function update_stock(){
    if(random_int(0, 100) <= 6){
      $the_event = $this->random_event();
      $this->update_price($the_event["change"]);
      $this->update_news($the_event["story"]);
    }else{
      $this->update_price();
      $this->update_news();
    }
  }

  function random_event(){
    $events = array(
       array("story"=> "positive quarterly financial results expected to be announced", "change"=> 3),
       array("story"=> "positive full year financial results expected to be announced", "change"=> 7),
       array("story"=> "negative quarterly financial results expected to be announced", "change"=> -4),
       array("story"=> "negative full year financial results expected to be announced", "change"=> -6),
       array("story"=> "beat the holy hell out of market expectations", "change"=> 3),
       array("story"=> "just barely market expectations", "change"=> 1),
       array("story"=> "hit market expectations exactly", "change"=> -2),
       array("story"=> "did not meet market expectations", "change"=> -5),
       array("story"=> "news of a buy out offer in the air", "change"=> random_int(-5, 28)),
       array("story"=> "crap! a supply shortage", "change"=> -2),
       array("story"=> "lost an important customer after disastrous lunch meeting", "change"=> -3),
       array("story"=> "supply costs way down", "change"=> random_int(1, 14)),
       array("story"=> "CEO featured on cover of Super Cool CEOs Magazine", "change"=> 3),
       array("story"=> "CEO resigned in disgrace", "change"=> -8),
       array("story"=> "CEO dies in kayaking accident", "change"=> -15),
       array("story"=> "CEO dies in freak boogie boarding accident", "change"=> -18),
       array("story"=> "CEO arrested for punching young girl's chinchilla", "change"=> -8),
       array("story"=> "employees unionize", "change"=> -4),
       array("story"=> "employees strike for better salad bar in cafeteria", "change"=> -1),
       array("story"=> "new industry regulations", "change"=> -2),
       array("story"=> "industry deregulation", "change"=> random_int(2, 10)),
       array("story"=> "acquired a weird competitor for too much money", "change"=> -5),
       array("story"=> "pulled off an amazing acquisition", "change"=> 5),
       array("story"=> "new dividends announced", "change"=> random_int(1, 8)),
       array("story"=> "dividend program ended", "change"=> -2),
       array("story"=> "mid-level managers investigated for cooking books", "change"=> -3),
       array("story"=> "insider trading investigation", "change"=> -3),
       array("story"=> "layoffs announced", "change"=> -2),
       array("story"=> "stock buy back announced", "change"=> random_int(1, 7)),
       array("story"=> "supplier disruption", "change"=> -2),
       array("story"=> "profits down 2000%", "change"=> random_int(-90, -70)),
       array("story"=> "profits down 200%", "change"=> random_int(-75, -40)),
       array("story"=> "profits down 20%", "change"=> -10),
       array("story"=> "profits down 2%", "change"=> -1),
       array("story"=> "profits up 2%", "change"=> -1),
       array("story"=> "profits up 20%", "change"=> 3),
       array("story"=> "profits up 200%", "change"=> random_int(5, 17)),
       array("story"=> "profits up 2000%", "change"=> random_int(10, 25)),
       array("story"=> "&dollar;1.6 billion (USD) class action lawsuit", "change"=> -15),
       array("story"=> "main competitor files for bankruptcy", "change"=> random_int(5, 20)),
       array("story"=> "factories flooded", "change"=> random_int(-19, -6)),
       array("story"=> "stock upgraded", "change"=> random_int(2, 6)),
       array("story"=> "stock downgraded", "change"=> -8),
       array("story"=> "featured on Buy It Stock TV Show", "change"=> 9),
       array("story"=> "added to Top Stocks To Buy List", "change"=> 12),
       array("story"=> "featured in the Floorsteet Journal", "change"=> 4),
       array("story"=> "stock genius Warren Puffin buys 20000 shares", "change"=> 19),
       array("story"=> "stock genius Warren Puffin sells all his shares", "change"=> random_int(-19, -11)),
       array("story"=> "amazing new product announced", "change"=> 18),
       array("story"=> "crappy new product announced", "change"=> -21),
       array("story"=> "sales up", "change"=> 7),
       array("story"=> "COO resigns to work for competitor", "change"=> -14),
       array("story"=> "CTO hired from competitor", "change"=> 13),
       array("story"=> "world reknown blogger Katy Kat recommends buying", "change"=> 4),
       array("story"=> "fire destroys warehouse", "change"=> -11),
       array("story"=> "fire destroys competitor's factory", "change"=> 8),
       array("story"=> "product praised by late night TV host, Sherman O'Odoul", "change"=> random_int(6, 20)),
       array("story"=> "product featured on Amazanan", "change"=> random_int(8, 15)),
       array("story"=> "Brud Pith tweets about company", "change"=> 7),
       array("story"=> "CEO insulted important congresswoman", "change"=> -5),
       array("story"=> "company janitor created new patent pending design", "change"=> random_int(1, 19)),
       array("story"=> "COO caught drunkenly peeing in bushes at local park", "change"=> -7),
       array("story"=> "voted top 10 places to work by Better Homes and Ponds", "change"=> 2),
       array("story"=> "voted top 10 places to work by Ranger Mick", "change"=> 1),
       array("story"=> "voted top 10 places to work by Margrot Stewhart Magazine", "change"=> -1),
       array("story"=> "lobbyist scores a win in the senate", "change"=> 2),
       array("story"=> "lobbyist scores a big win in the senate", "change"=> 8),
       array("story"=> "company-wide fight club uncovered", "change"=> -3),
       array("story"=> "CTO arrested for armed robbery", "change"=> -20),
       array("story"=> "chairman's son makes a hit music video", "change"=> random_int(15, 30)),
       array("story"=> "18-25 year old customers have abandoned the company", "change"=> random_int(-15, -8)),
       array("story"=> "product recall after customer's pants catch on fire", "change"=> random_int(-15, -10)),
       array("story"=> "CEO sells all her stocks", "change"=> -60),
       array("story"=> "hipsters discover product", "change"=> random_int(20, 45)),
       array("story"=> "hipsters abandon product", "change"=> random_int(-50, -20)),
       array("story"=> "FBI raids headquarters", "change"=> random_int(-50, -35)),
       array("story"=> "police sting uncovers bum fights", "change"=> random_int(-15, -5)),
       array("story"=> "CEO quits to pursue banjo career", "change"=> random_int(-25, -5)),
       array("story"=> "FCC fine company for false advertising", "change"=> random_int(-15, -5)),
       array("story"=> "customer database hacked", "change"=> random_int(-30, -15)),
       array("story"=> "CEO has email hacked", "change"=> random_int(-10, -5)),
       array("story"=> "CEO goes on ENN news show", "change"=> random_int(5, 10)),
       array("story"=> "customer support wins award", "change"=> random_int(10, 15)),
       array("story"=> "product added to list of super hot x-mas buys", "change"=> random_int(10, 15)),
       array("story"=> "CEO writes best selling book", "change"=> random_int(5, 10))
    );
    shuffle($events);
    return $events[random_int(0, (sizeof($events)-1) )];
  }

  //      "interest rate raised"=>0,
  //      "interest rate lowered"=>1,
  //      "amazing president elected"=>1,
  //      "horrible president with small hands elected"=>0,
  //      "consumer spending up"=>1,
  //      "consumer spending down"=>0,
  //      "military action announced"=>0,


  function random_name($name_word_length=2){
    $word_list = Array(
     'above','accident','act','activist','actor','add','administration','adviser','against','age','agency','aggression','agriculture','aid','aim','air','airplanes','airport','all','ally','almost','always','ambassador','amend','ammunition','among','amount','anarchy','ancient','anger','animal','anniversary','announce','another','answer','ape','apologize','appeal','appear','appoint','approved','area','argue','arms','army','around','arrest','arrive','art','artillery','ash','ask','assist','astronaut','asylum','atmosphere','atom','attack','attempt','attend','automobile','autumn','awake','award','away',
         'bad','balance','ball','balloon','ballot','bank','bar','base','battle','beach','beat','beauty','because','become','bed','beg','begin','bells','below','best','betray','better','big','bill','bird','bite','bitter','black','blame','blanket','bleed','blind','block','blood','blow','blue','boat','body','boil','bomb','bone','book','border','born','borrow','both','bottle','bottom','box','boy','brain','brave','bread','break','breathe','bridge','brief','bright','broadcast','brother','brown','builders','bullet','burn','burst','bus','business',
         'cabinet','call','calm','camera','campaign','can','cancel','cancer','candidate','cannon','capital','capture','car','care','careful','carry','case','cat','catch','cattle','celebrate','cell','center','century','ceremony','chairman','champion','chance','change','charge','chase','cheat','check','cheer','chemicals','chieg','child','choose','church','circle','citizen','city','civil','civilian','clash','clean','clear','climb','clock','close','cloth','clothes','cloud','coal','coalition','coast','coffee','cold','collect','colony','color','comedy','command','comment','committee','common','communicate','company','compete','complete','compromise','computer','concern','condemn','condition','conference','confirm','conflict','congratulate','congress','connect','conservative','consider','contain','continent','continue','control','convention','cook','cool','cooperate','copy','correct','cost','costitution','cotton','count','country','court','cover','cow','coward','crash','create','creature','credit','crew','crime','criminal','crisis','criticize','crops','cross','crowd','cruel','crush','culture','cures','current','custom','cut',
         'damage','dance','danger','dark','date','daughter','day','deal','debate','decide','declare','deep','defeat','defend','deficit','degree','delay','delegate','demand','demonstrate','denounce','deny','depend','deplore','deploy','describe','desert','design','desire','destroy','details','develop','device','different','difficult','dig','dingo','dinner','diplomat','direct','direction','dirty','disappear','disarming','discoveries','disease','dismiss','dispute','dissident','distance','distant','divide','doctor','document','dollar','door','down','draft','dream','drink','drive','drown','dry','dust','duty',
         'each','early','earn','earth','earthquake','ease','east','easy','eat','economy','edge','educate','effect','effort','egg','either','elect','electricity','electron','element','embassy','emergency','emotion','employ','empty','end','enemy','energy','enforce','engine','engineer','enjoy','enough','enter','eqipment','equal','escape','especially','establish','even','event','evidence','evil','evironment','exact','examine','example','excellent','exchange','excite','excuse','execute','exile','exist','expand','expect','expel','experiments','experts','explain','explode','explore','export','express','extend','extra','extreme',
         'face','fact','factory','fail','fair','fall','family','famous','fanatic','far','farm','fast','fat','fear','feast','federal','feed','feel','few','field','fierce','fight','fill','film','final','find','fine','finish','fire','firm','first','fish','fix','flag','flat','flee','float','flood','floor','flow','flowers','fluid','fly','foal','fog','follow','food','fool','foot','force','foreign','forget','form','former','forward','free','freeze','fresh','friend','frighten','front','fruit','fuel','funeral','furious','future',
         'gain','game','gas','gather','general','gentle','gift','girl','glass','goats','gold','good','goods','govern','grift','grain','grass','gray','great','green','grind','ground','group','grow','guarantee','guard','guerilla','guide','guilty','gun','gin','gumption','gangly',
         'hair','half','halt','hang','happen','happy','harbor','hard','harm','hat','hate','head','headquarters','health','hear','heart','heat','heavy','helicopter','help','hero','hide','high','hijack','hill','history','hit','hold','hole','holiday','holy','home','honest','honor','hope','horrible','horse','hospital','hostage','hostile','hostilities','hot','hotel','hour','house','how','however','huge','human','humor','hunger','hunt','hurry','hurt',
         'ice','ideas','illegal','imagine','immediate','import','important','improve','incident','incite','include','increase','independent','industry','inflation','influence','inform','injure','innocent','insane','insect','inspect','instead','instrument','insults','intelligence','intense','interest','international','intervene','invade','invent','invest','investigations','invite','involve','iron','island','issue',
         'jails','jewels','jobs','joke','judge','jumpy','jungle','jury','just','joust','jack','jangly','jazzy','jet','jerky','jawless','jejune','jittery','joyless','joyful','jumpy','jilted','jerky','jovial','jaunty','jocund','jocose','juxtaposed','juvenile','jumbled','jowly',
         'keep','kick','kind','kiss','knife','know','knot','kangaroo','kiln','kilt','knight','kite','kernel','knack','klutz','kinky','kooky','king','kenetic','kindly','kindle','kitted','kissed','keen','knobby','knighted','key','kosher','knotty','knavish','kitschy',
         'labor','laboratory','lack','lake','land','language','large','last','late','laugh','launch','law','lead','leak','learn','leave','left','legal','lend','less','let','letter','level','lie','life','light','lightning','like','limit','line','link','liquids','list','listen','little','live','load','local','lonely','long','look','lose','loud','love','low','loyal','luck',
         'machine','mad','mail','main','major','majority','make','maps','march','mark','marker','mass','material','may','mayor','mean','measure','meat','medicine','meet','melt','member','memorial','memory','mercenary','mercy','message','metal','method','microscope','middle','militant','military','milk','mind','mine','mineral','minister','minor','minority','minute','miss','missile','missing','mistake','mix','mob','modern','money','month','moon','more','morning','most','mother','motion','mountain','mourn','move','music','mystery',
         'naked','name','nation','navy','near','necessary','negotiate','neither','nerve','neutral','never','new','news','next','nice','night','noise','nominate','noon','normal','north','note','nothing','nowhere','nuclear','number','nurse','naive','nautical','negligible','nethermost',
         'obey','object','observe','occupy','ocean','offensive','offer','officer','official','often','oil','old','open','operate','opinion','oppose','opposite','oppress','orbit','orchestra','order','organize','other','overthrow','obsolescent','offshore','odious','oblong','offbeat','olympic',
         'pain','paint','palace','pamphlet','pan','paper','parachute','parade','pardon','parent','parliament','part','party','pass','passenger','passport','past','path','pay','peace','people','percent','perfect','perhaps','period','permanent','permit','person','physics','piano','picture','piece','pilot','pipe','pirate','place','planet','plant','play','please','plenty','plot','poem','point','poison','police','policy','politics','pollute','poor','popular','population','port','position','possess','possible','postpone','pour','power','praise','pray','pregnant','prepare','present','president','press','pressure','prevention','price','priest','prison','private','prize','probably','problem','produce','professor','program','progress','project','promise','propaganda','property','propose','protect','protest','proud','prove','provide','public','publication','publish','pumps','purchase','pure','purpose',
         'question','quick','quiet','quilt','quail','quaint','quality','quest','qualified','quenched','queasy','quizzical','quantified','qualm','query','quieter','questionable','quotable','quiver','quotes','quack','quake','quartz','quarrelsome','questioning','quelled',
         'rabbi','race','radar','radiation','radio','raid','railroad','rain','raise','rapid','rare','rate','reach','read','ready','real','realistic','reason','reasonable','rebel','receive','record','red','refugee','relations','release','remain','remember','remove','repair','repeat','report','repress','request','rescue','resign','resolution','responsible','rest','restrain','restrict','result','retire','return','revolt','rice','rich','ride','right','riot','rise','river','road','rock','rocket','roll','room','root','rope','rough','round','rub','rubber','ruin','rule','run',
         'sabotage','sacrifice','safe','sail','salt','same','satellite','satisfy','save','say','school','science','scream','sea','search','season','seat','second','secret','security','see','seek','seem','seize','self','sense','sentence','separate','series','serious','sermon','settle','several','severe','shake','shape','share','sharp','shell','shine','ship','shock','shoe','shoot','short','shout','show','shrink','shut','sick','side','sign','signal','silence','silver','similar','simple','since','sing','sink','skeleton','skill','skull','sky','slave','sleep','slide','slow','small','smash','smile','smoke','smooth','snow','social','soft','soldier','solid','solve','some','soon','sorry','sound','south','space','speak','special','speed','spend','spill','spilt','spirit','split','sports','spread','spring','spy','stab','stamp','stand','star','start','starve','state','station','statue','steal','steam','steel','step','stick','stomach','stone','stop','store','storm','story','stove','straight','strange','street','stretch','strong','struggle','stubborn','study','stupid','submarine','substance','substitute','subversion','succeed','such','sudden','suffer','sugar','summer','sun','supervise','supply','support','suppose','suppress','sure','surplus','surprise','surrender','surround','survive','suspect','suspend','swallow','swear','sweet','swim','sympathy','system',
         'talk','tall','tank','target','task','taste','tax','teaching','team','tear','tears','technical','telephone','telescope','television','tell','temperature','temporary','term','terrible','territory','terror','test','textiles','thank','that','theater','thick','thin','thing','think','third','threaten','through','throw','tie','time','tired','tissue','today','together','tomorrow','tonight','tool','top','torture','touch','toward','town','trade','tradition','tragic','train','traitor','transport','trap','travel','treason','treasure','treat','treaty','tree','trial','tribe','trick','trip','troops','trouble','truck','trust','turn',
         'under','understand','unite','universe','university','up','urge','urgent','usual','valley','value','vehicle','version','veto','vicious','victim','victory','village','violate','violence','violin','virus','visit','voice','volcano','vote','voyage','verbose','vincible','visualized',
         'wages','walk','wallpaper','warm','warn','wash','waste','water','wave','way','weak','wealth','weapon','wear','weather','weigh','welcome','well','west','wet','wheat','wheel','white','wide','wild','wind','window','wire','wise','wish','withdraw','without','woman','wonder','wood','woods','word','work','world','worry','worse','wound','wreck','write','wrong',
         'year','yellow','yesterday','young','yeti','yogurt','yelp','yard','yearning','you','yucky','yonder','younger','youthful','yuppy','yin','yang','yak','yokai',
         'zebra','zoo', 'zing','zipper','zit','zepplin','zapper','zany','zombie','zonked','zinc','zenith','zesty','zippy','zoological','zygote','zigzag','zebra'
    );
    $name = "";
    for($i=0; $i<$name_word_length; $i++){
      $name .= $word_list[random_int(0, sizeof($word_list))].' ';
    }
    return rtrim($name);
  }
}
?>
