---
marp: true
theme: gaia
title: Quantainer
author: mazrean
url: https://slides.mazrean.com/ddd/
image: https://slides.mazrean.com/ddd/ogp.png
paginate: true
---
<style>
  :root {
    --color-background: #f2f4f7;
    --color-foreground: #111f4d;
    --color-highlight: rgba(17, 31, 77, 0.5);
    --color-dimmed: #757575;
  }
  :root.invert {
    --color-background: #111f4d;
    --color-foreground: #f2f4f7;
    --color-highlight: rgba(17, 31, 77, 0.5);
    --color-dimmed: #757575;
  }
</style>
<!--
_class:
- invert
- lead
_paginate: false
-->

# DDDから学ぶ設計講習会
## @mazrean

---
# 目次

1. DDD
2. Hexagonal Architecture
3. Clean Architecture
4. デザインパターンと設計
5. 設計にできることとできないこと
6. 良い設計とは

---
# 注意

今回の講習会での各アーキテクチャの説明は
以下の書籍・Webサイトをベースにしています
(詳細情報は参考文献に書いています)

- DDD: エリック・エヴァンスのドメイン駆動設計
- Hexagonal Architecture: [Hexagonal architecture](https://alistair.cockburn.us/hexagonal-architecture/)
- Clean Architecture: [The Clean Architecture - The Clean Code Blog](https://blog.cleancoder.com/uncle-bob/2012/08/13/the-clean-architecture.html)

---
<!--
_class:
- invert
- lead
_paginate: false
-->
# DDD

---
# Layered Architecture

アプリケーションを層で分割するアーキテクチャ
- UI層: ユーザーからの入出力
- Application層:
  アプリケーションならではの処理(ビジネスロジック)
- Domain層:
  アプリケーションで満たすべき仕様
- Infrastructure層:
  技術的な機能の実装（ex:DBへのSQL実行）

![bg right:25% w:170](layered-architechture.drawio.svg)

---
# Layered Architectureのメリット

層で分割されているので、ユニットテストが書きやすい。

1. 直下の層のmockを作成
1. mockを使用してテストを書く

---
# Domain Driven Design(DDD)

DDDはLayered Architectureの中のDomain層を中心にして行うアプリケーションの設計。
物凄く簡単にいうと、「仕様に従うコードを書ける設計をするべき」ということ。

---
# Layered Architectureの問題点

Domain層**が**Infrastructure層に依存

- Infrastructure層でDomain層のルールが守られない可能性
- Domain層のルールをInfrastructure層をもとに決めてしまいやすい

つまり、愚直に実装するとDDD的に良くない！

![bg right:25% w:170](layered-architechture.drawio.svg)

---
# 解決策

Domain層とInfrastructure層の依存関係を逆にすれば解決する

![w:300](layered-architechture-to-ddd.drawio.svg)

---
# Dependency Injection(DI)

間にinterfaceを挟むことで、
依存の向きを逆にできる

![w:600](inversion-of-control.drawio.svg)

---
# 注意点

interfaceの定義時には依存される側(今回ならInfrastructure)の
データしか使ってはいけない

![w:600](inversion-of-control.drawio.svg)

---
# DI Container

interfaceにclass(Goならstruct)を割り当てるコードを自動生成するツール。

Javaの[Spring Boot](https://spring.io/projects/spring-boot)のやつが有名。
Goの場合、[wire](https://github.com/google/wire)を使うことが多い。

---
<!--
_class:
- lead
-->
![75%](wire.png)

![bg right:50% 75%](wire-gen.png)

---
# Layered Architecture with DI

DIでLayered ArchitectureのDomain層とInfrastructure層の依存を逆転させる

![bg right:40% w:150](ddd-basic.drawio.svg)

---
# メリット

- 仕様がコード全体で守られる
- 技術が変わっても仕様に関するコードを変えずに済む
  - 変化の速度は基本$技術 \gg 仕様$
    →変更が大幅に削れる

---
# Domain

Domainには3種類存在する

- Entity
- Value Object
- Service

---
# 例として使うアプリケーション

シンプルなチャットアプリ。
ユーザーがメッセージを投稿できる。

![bg right:50% w:500](example-app.drawio.svg)

---
# Service

ものに紐づかない操作。

ex) メッセージの投稿。

---
# Entity

概念として識別が可能なドメイン。

ex) User,Message
@mazreanと@tokiは別のユーザー。

---
# Value Object

概念として識別ができないドメイン。

ex) ユーザー名
mazreanという文字列のユーザー名と、
tokiという文字列のユーザー名はそれ自体で識別することはない。
ユーザーになって初めて識別ができるものになる。

---
# 演習: Domainを見てみよう
時間: 2min
traP CollectionのサーバーサイドはDDDベースのアーキテクチャになっている。ドメインを見てみよう!
https://github.com/traPtitech/trap-collection-server

- Entity: src/domain直下
- Value Object: src/domain/value以下
- Service: src/service**直下**

---
<!--
_class:
- invert
- lead
_paginate: false
-->
# Hexagonal Architecture

---
# Hexagonal Architecture

DDDに対称性という考えを加えたもの
Port, Adapterという見方が特徴

![bg right:50% w:80%](hexagonal-original.png)

---
# Layered Architecture→Hexagonal Architecture

- **InfrastructureがApplicationに依存**
- Domainへの言及がない

![h:250](ddd-to-hexagonal.drawio.svg)

---
# メリット

- 対称性
- Port, Adapterという見方のイメージしやすさ

![bg right:50% w:80%](hexagonal-original.png)

---
# 対称性

**Hexagonal** Architectureという名前の由来

- 対象である方が層の境界が分かりやすい
- 3つ以上の外部アプリケーションが存在する場合のイメージがしやすい
  - Layered Architectureの図は2方向

![bg right:50% w:80%](hexagonal-original.png)

---
# Port, Adapter

異なる接続口を持つ外部アプリケーションを
アプリケーションの用意したPortに合うように
Adapterで変換してPortに繋ぐ

![h:200](port-adapter.jpeg)![h:200](hexagonal-original.png)

---
# Port, Adapter

USB Type-Aのマウス(外部アプリケーション)を
USB Type-CへAdapterで変換して
PC(アプリケーション)に接続するイメージ

![h:200](port-adapter.jpeg)![h:200](hexagonal-original.png)

---
# 演習: Port, Adapterを見てみよう
時間: 2min
traP CollectionのサーバーサイドにもPort, Adapterの構造が存在する。
(Hexagonal Architectureではない)
repository(データ永続化)のPort, Adapterを見てみよう!
https://github.com/traPtitech/trap-collection-server

- Port: src/repository**直下**
- Adapter:
  - src/repository/gorm直下
  - src/repository/mock直下(`go generate`すると生成されます)

---
<!--
_class:
- invert
- lead
_paginate: false
-->
# Clean Architecture

---
<!--
_footer: 引用：[The Clean Architecture 13 August 2012 - Clean Coder Blog](https://blog.cleancoder.com/uncle-bob/2012/08/13/the-clean-architecture.html)
-->
# Clean Architecture

Hexagonal ArchitectureやOnion Architectureなどを統合して、
より実用的にしようとしたもの

![bg right:50% w:80%](clean-architecture.jpeg)

---
# Layered Architecture→Clean Architecture

Domainが消えていないHexagonal Architecture

![h:300](ddd-to-clean.drawio.svg)

---
# メリット

DDDのメリット+Hexagonal Architectureのメリット

- 仕様がコード全体で守られる(DDD)
- 技術が変わっても仕様に関するコードを変えずに済む(DDD)
- 対称性(Hexagonal Architecture)

---
# DDD関連のアーキテクチャの関係

![h:450](ddd-relation.drawio.svg)

---
<!--
_class:
- invert
- lead
_paginate: false
-->
# 休憩
## 10min
![h:150](dad-parrot.gif)

---
# Adapter Pattern

欲しいinterfaceに合わないライブラリなどのinterfaceを、
欲しいinterfaceに合わせるデザインパターン

![](adapter-pattern.drawio.svg)

---
# 最後に

ここまで長いものでなくて良いので、講習会をやってみてください！

- 知識が整理できる
- 興味がある分野に周りが興味を持ってくれて、刺激を受けられる可能性が上がる
- やっていた仕事を周りに任せられる

など、良いことが無限にあります。
