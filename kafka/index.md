**教程**
https://www.jianshu.com/p/cc540f29e779

http://www.infoq.com/cn/articles/kafka-analysis-part-4/


**查看创建的Topic**

./bin/kafka-topics --list --zookeeper localhost:2181

**生产数据**

./bin/kafka-console-producer --broker-list localhost:9092 --topic test1

**消费数据**

./bin/kafka-console-consumer.sh --zookeeper localhost:2181 --topic test1--from-beginning


Message(消息)：传递的数据对象，主要由四部分构成：offset(偏移量)、key、value、timestamp(插入时间)； 其中offset和timestamp在kafka集群中产生，key/value在producer发送数据的时候产生

Broker(代理者)：Kafka集群中的机器/服务被成为broker， 是一个物理概念。

Topic(主题)：维护Kafka上的消息类型被称为Topic，是一个逻辑概念。

Partition(分区)：具体维护Kafka上的消息数据的最小单位，一个Topic可以包含多个分区；Partition特性：ordered & immutable。(在数据的产生和消费过程中，不需要关注数据具体存储的Partition在那个Broker上，只需要指定Topic即可，由Kafka负责将数据和对应的Partition关联上)

Producer(生产者)：负责将数据发送到Kafka对应Topic的进程

Consumer(消费者)：负责从对应Topic获取数据的进程

Consumer Group(消费者组)：每个consumer都属于一个特定的group组，一个group组可以包含多个consumer，但一个组中只会有一个consumer消费数据。